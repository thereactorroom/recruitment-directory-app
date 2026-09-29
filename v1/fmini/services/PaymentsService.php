<?php 

class PaymentsService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function generatePayFastIdentifier($input){
        $listingModel = new ListingsModel($this->db);
        $subscriptionModel = new SubscriptionsModel($this->db); 

        $amount = (float)$input->amount;
        $listing = $listingModel->get($input->listing_id);
        $subscription = $subscriptionModel->find([
            "unique_key_id" => $input->unique_key_id,
            "listing_id" => $input->listing_id,
        ]);
        
        $increment = $billing_cycle = "";
        if ($input->amount == "99") {
            $increment = "+30 days";
            $billing_cycle = "Monthly";
        } else if ($input->amount == "270") {
            $increment = "+90 days";
            $billing_cycle = "Quarterly";
        } else if ($input->amount == "495") {
            $increment = "+6 months";
            $billing_cycle = "Bi-Annual";
        } else if ($input->amount == "990") {
            $increment = "+1 years";
            $billing_cycle = "Annual";
        } else if ($input->amount == "5") {
            $increment = "+30 days";
            $billing_cycle = "Test Payment";
        }
        
        $amount = (float)$input->amount;
        $payment_date = $this->utils::getDateTime();
        $subscriptionModel->update([
            "id" => $subscription->id,
            "first_payment" => $amount,
            "recurring_payment" => $amount,
            "billing_cycle" => $billing_cycle,
            "last_billing" => $payment_date,
            "next_billing" => $this->utils::incrementDateTime($increment, $payment_date),
        ]);

        $fusionHost = SANDBOX ? 'https://uat.fusiononq.com' : 'https://app.fusiononq.com';
        $data = [
            // Merchant details
            'merchant_id' => PAYFAST_MERCHANT_ID,
            'merchant_key' => PAYFAST_MERCHANT_KEY,
            'notify_url' => $fusionHost . '/modules/module_dev/recruitment_directory/v1/payments/notify',
            
            // Buyer details
            'name_first' => $listing->name,
            'email_address' => $listing->email,
            
            // Transaction details
            'm_payment_id' => $listing->id . '-' . $subscription->id,
            'amount' => number_format(sprintf("%.2f", $amount), 2, '.', ''),
            'item_name' => 'Physio Directory Subscription',
            'item_description' => $listing->name . ' ' . $billing_cycle . ' Physio directory subscription'
        ];

        // Generate signature (see Custom Integration -> Step 2)
        $data["signature"] = $this->generateSignature($data);
        
        // Convert the data array to a string
        $pfParamString = $this->dataToString($data);

        // Generate payment identifier
        $identifier = $this->generatePaymentIdentifier($pfParamString); 
        return $identifier;
    }

    function generateSignature($data) {
        // Create parameter string
        $pfOutput = '';
        foreach($data as $key => $val) {
            if($val !== '') {
                $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }
        // Remove last ampersand
        $getString = substr( $pfOutput, 0, -1 );
        if (PAYFAST_PASSPHRASE !== null) {
            $getString .= '&passphrase='. urlencode(PAYFAST_PASSPHRASE);
        }
        return md5($getString);
    } 

    function dataToString($dataArray) {
        // Create parameter string
        $pfOutput = '';
        foreach( $dataArray as $key => $val ) {
            if($val !== '') {
                $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
            }
        }
        // Remove last ampersand
        return substr( $pfOutput, 0, -1 );
    }

    function generatePaymentIdentifier($pfParamString, $pfProxy = null) {
        // Use cURL (if available)
        if(in_array( 'curl', get_loaded_extensions(), true )) {

            // Create default cURL object
            $ch = curl_init();

            // Set cURL options - Use curl_setopt for greater PHP compatibility
            // Base settings
            curl_setopt( $ch, CURLOPT_USERAGENT, NULL );            // Set user agent
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );       // Return output as string rather than outputting it
            curl_setopt( $ch, CURLOPT_HEADER, false );              // Don't include header in output
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true );

            // Standard settings
            curl_setopt( $ch, CURLOPT_URL, PAYFAST_PROCESS_URL );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $pfParamString );
            if( !empty( $pfProxy ) )
                curl_setopt( $ch, CURLOPT_PROXY, $pfProxy );

            // Execute cURL
            $response = curl_exec( $ch );
            curl_close( $ch );

            $rsp = json_decode($response, true);
            if ($rsp['uuid']) {
                return $rsp['uuid'];
            }
        }
        return null;
    }

    public function verifyPaymentTransaction($data){
        // Strip any slashes in data
        $pfData = [];
        foreach($data as $key => $val) {
            $pfData[$key] = stripslashes($val);
        }

        // Convert posted variables to a string
        $pfParamString = "";
        foreach($pfData as $key => $val) {
            if( $key !== 'signature' ) {
                $pfParamString .= $key .'='. urlencode( $val ) .'&';
            }
        }
        $pfParamString = substr( $pfParamString, 0, -1 ); 

        $checkSignature = $this->pfValidSignature($pfData, $pfParamString);

        return $checkSignature;
    }

    public function pfValidSignature($pfData, $pfParamString) {
        // Calculate security signature
        if(PAYFAST_PASSPHRASE === null) {
            $tempParamString = $pfParamString;
        } else {
            $tempParamString = $pfParamString.'&passphrase='.urlencode( PAYFAST_PASSPHRASE );
        }

        $signature = md5($tempParamString);
        // print_r($pfData['signature'] . ' === ' . $signature);
        return ($pfData['signature'] === $signature);
    }

    public function savePayFastResponseToLog($subscription_id, $status, $data): void {
        $subscriptionModel = new SubscriptionsModel($this->db);
        $payFastResponseLogModel = new PayFastResponseLogModel($this->db);

        if ($subscription_id) {
            $subscription = $subscriptionModel->get($subscription_id);
            $payFastResponseLogModel->insert([
                'unique_key_id' => $subscription->unique_key_id,
                'subscription_id' => $subscription->id,
                'status' => strtolower($status),
                'payload' => json_encode($data),
            ]);
        } else {
            $payFastResponseLogModel->insert([
                'unique_key_id' => 0,
                'subscription_id' => 0,
                'status' => strtolower($status),
                'payload' => json_encode($data),
            ]);
        }
    }

    public function monthlyLog($input) {

        $start_date = $this->utils::getDate($input->start_date);
        $last_date = $this->utils::incrementDate("+30 days", $start_date);

        $where = " and 
            payment_logs.unique_key_id = :unique_key_id and 
            payment_logs.added between :start_date and :last_date
        ";
        
        $this->db->prepareStatement($this->selectStatement($where, ""));
        $this->db->execute([
            "unique_key_id" => $input->unique_key_id,
            "start_date" => $start_date,
            "last_date" => $last_date
        ]);

        $paymentLogs = $this->db->fetchAll();
        
        $rows = [];
        foreach ($paymentLogs as $paymentLog) {
            $rows[] = [
                // $paymentLog->person_name,
                // $paymentLog->person_surname,
                $paymentLog->name,
                $this->utils::formateDateTime($paymentLog->added),
                $paymentLog->amount,
                $paymentLog->method,
                $paymentLog->comment
            ];
        }
        
        $file_name = $input->unique_key_id . "_" . $start_date . ".csv";
        $csv_file = ENV_PATH . "recruitment_directory/logs/" . $file_name;
        $fp = fopen($csv_file, 'w');
        
        fputcsv($fp, ['Name', 'Date', 'Amount', 'Method', 'Comment']);
        foreach ($rows as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);

        return ENV_HOST . "/modules/module_dev/recruitment_directory/logs/" . $file_name;
    }

    public function checkAvailableLog($input) {
        $s_query = "
            select 
                distinct DATE_FORMAT(added, '%Y-%m') as unique_month_year,
                DATE_FORMAT(added, '%Y') as year,
                DATE_FORMAT(added, '%M') as month_text,
                DATE_FORMAT(added, '%m') as month_number
            from 
                payment_logs
            where 
                unique_key_id = :unique_key_id and 
                deleted = ''
        ";
        $this->db->prepareStatement($s_query);
        $this->db->execute(["unique_key_id" => $input->unique_key_id]);

        return $this->db->fetchAll();
    }

    public function selectStatement($where = "", $limit = "") {
        $s_query = "
            select 
                payment_logs.id,
                payment_logs.unique_key_id,
                payment_logs.listing_id,
                payment_logs.subscription_id,
                payment_logs.date_paid,
                payment_logs.amount,
                payment_logs.method,
                payment_logs.comment,
                payment_logs.added,
                listings.name
            from payment_logs
                inner join listings on listings.id = payment_logs.listing_id
            where 
                payment_logs.deleted = '' $where 
            order by payment_logs.added desc 
            $limit
        ";
        return $s_query;
    }

}


// public function generatePayFastForm($input){
//         $businessModel = new ListingsModel($this->db);
//         $subscriptionModel = new SubscriptionsModel($this->db); 

//         $business = $businessModel->get($input->business_id);
//         $subscription = $subscriptionModel->find([
//             "unique_key_id" => $input->unique_key_id,
//             "business_id" => $input->business_id,
//         ]);

//         $amount = 15; //$subscription->first_payment
//         $host = SANDBOX ? 'https://uat.fusiononq.com' : 'https://app.fusiononq.com';
//         $data = [
//             // Merchant details
//             'merchant_id' => MERCHANT_ID,
//             'merchant_key' => MERCHANT_KEY,
//             'return_url' => $host . '/modules/module_dev/service_directory/v2/payments/return',
//             'cancel_url' => $host . '/modules/module_dev/service_directory/v2/payments/cancel',
//             'notify_url' => $host . '/modules/module_dev/service_directory/v2/payments/notify',

//             // Buyer details
//             'name_first' => $listing->person_name,
//             'name_last' => $listing->person_surname,
//             'email_address' => $listing->email,
            
//             // Transaction details
//             'm_payment_id' => $listing->id . '-' . $subscription->id,
//             'amount' => number_format(sprintf("%.2f", $amount), 2, '.', ''),
//             'item_name' => 'Business Directory Subscription',
//             'item_description' => $listing->business_name . ' Annual Business directory subscription'
//         ];
//     }

// $host = SANDBOX ? 'https://uat.fusiononq.com' : 'https://app.fusiononq.com';
//         $data = [
//             // Merchant details
//             'merchant_id' => MERCHANT_ID,
//             'merchant_key' => MERCHANT_KEY,
//             'return_url' => $host . '/modules/module_dev/service_directory/v2/payments/return',
//             'cancel_url' => $host . '/modules/module_dev/service_directory/v2/payments/cancel',
//             'notify_url' => $host . '/modules/module_dev/service_directory/v2/payments/notify',

//             // Buyer details
//             'name_first' => $listing->person_name,
//             'name_last' => $listing->person_surname,
//             'email_address' => $listing->email,

//             // Transaction details
//             'm_payment_id' => $listing->id . '-' . $subscription->id,
//             'amount' => number_format( sprintf( "%.2f", $subscription->first_payment ), 2, '.', '' ),
//             'item_name' => 'Business Directory Subscription',
//             'item_description' => $listing->business_name . ' Annual Business directory subscription'
//         ];

//         $pfOutput = '';
//         foreach($data as $key => $val){
//             if(!empty($val)){
//                 $pfOutput .= $key .'='. urlencode(trim($val)) .'&';
//             }
//         }
//         // Remove last ampersand
//         $getString = substr( $pfOutput, 0, -1 );

//         $getString .= '&passphrase='. urlencode(trim(PASSPHRASE));
//         $data['signature'] = md5($getString);

//         $pfHost = SANDBOX ? 'https://sandbox.payfast.co.za/onsite/process' : 'https://www.payfast.co.za/onsite/process';

//         // $html = '<form action="https://'.$pfHost.'/eng/process" method="post">';
//         // foreach($data as $name => $value) {
//         //     $html .= '<input type="hidden" class="f-input" name="'.$name.'" value="'.$value.'" >';
//         // }
//         // $html .= '<button class="" type="submit">Pay Now with PayFast</button></form>';        
//         // $data['host'] = $pfHost;