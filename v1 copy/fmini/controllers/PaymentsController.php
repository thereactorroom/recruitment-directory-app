<?php 

class PaymentsController extends Controller {
    
    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }

    public function checkAvailableLog(){
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new PaymentsService();
            $data = $service->checkAvailableLog($this->input);
            return $this->utils::response(true, "Operation successful", $data);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function monthlyLog(){
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new PaymentsService();
            $data = $service->monthlyLog($this->input);
            return $this->utils::response(true, "Operation successful", $data);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function generatePayFastIdentifier(){
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new PaymentsService();
            $data = $service->generatePayFastIdentifier($this->input);
            return $this->utils::response(true, "Operation successful", $data);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function notify() {
        // if ($this->method != "post") {
        //     return utils::response(false, "Invalid request method");
        // }
        header('HTTP/1.0 200 OK');
        flush();
        try {
            // if (!isset($this->input)) {
            //     return $this->utils::response(false, "Invalid request inputs");
            // }
            // $data = [
            //     "item_name" => "Business Directory Subscription",
            //     "name_last" => "Miller",
            //     "signature" => "0fa5b9045cab88b84343b29c8e064946",
            //     "amount_fee" => "-5.94",
            //     "amount_net" => "93.06",
            //     "name_first" => "Rosana",
            //     "custom_int1" => "",
            //     "custom_int2" => "",
            //     "custom_int3" => "",
            //     "custom_int4" => "",
            //     "custom_int5" => "",
            //     "custom_str1" => "",
            //     "custom_str2" => "",
            //     "custom_str3" => "",
            //     "custom_str4" => "",
            //     "custom_str5" => "",
            //     "merchant_id" => "32127315",
            //     "amount_gross" => "99.00",
            //     "m_payment_id" => "787-799",
            //     "email_address" => "Rosana@millerstravel.co.za",
            //     "pf_payment_id" => "281061907",
            //     "payment_status" => "COMPLETE",
            //     "item_description" => "Miller's Travel  Monthly Business directory subscription"
            // ];

            $data = $_POST;
            $explodes = explode('-', $data['m_payment_id']);
            $business_id = $explodes[0];
            $subscription_id = $explodes[1];

            $service = new PaymentsService();
            $service->savePayFastResponseToLog($subscription_id, $data['payment_status'], $data);
            // echo '<pre/>';
            if ($data['payment_status'] != "COMPLETE") {
                return $this->utils::response(false, "Payment could not be processed");
            }

            // echo "payment status is complete";
            // $response = $service->verifyPaymentTransaction($data);
            // print_r($response);
            // if (!$response) {
            //     return $this->utils::response(false, "Payment could not be verified");
            // }            

            $this->input->id = $business_id;
            $this->input->subscription_id = $subscription_id;
            $this->input->amount = $data['amount_gross'];

            $service = new ListingsService();
            $response = $service->payBusiness($this->input, $data);

            if (!$response) {
                return $this->utils::response(false, "Payment could not be processed");
            }
            return $this->utils::response(true, "Operation successful");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function notify2() {
        header('HTTP/1.0 200 OK');
        flush();
        try {
            $data = $_POST;
            
            $paymentService = new PaymentsService();
            $businessService = new ListingsService();
            $subscriptionModel = new SubscriptionsModel($this->db); 

            $subscription = $subscriptionModel->find(['business_id' => $data['item_description'], 'paid' => 0]);
            $paymentService->savePayFastResponseToLog($subscription->id, $data['payment_status'], $data);

            if ($data['payment_status'] != "COMPLETE") {
                return $this->utils::response(false, "Payment could not be processed");
            }          

            $this->input->id = $subscription->business_id;
            $this->input->subscription_id = $subscription->id;
            $this->input->amount = $data['amount_gross'];

            $response = $businessService->payBusiness($this->input, $data);
            if (!$response) {
                return $this->utils::response(false, "Payment could not be processed");
            }
            return $this->utils::response(true, "Operation successful");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}