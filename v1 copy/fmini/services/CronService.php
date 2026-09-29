<?php 

class CronService extends Service {
    
    public function __construct() {
        parent::__construct();
        if (empty($this->db)) {
            die('Invalid database connection');
        }
    }

    public function checkExpiredAndDowngrade() {
        $today = $this->utils::getDate();
        $response[] = 'today: ' . $today;
        $response[] = "----------------------------------------";

        try {
            $businessService = new ListingsService();
            $where = " and 
                subscriptions.next_billing < :next_billing and 
                subscriptions.status = :status and 
                subscriptions.paid = :paid
            ";
            $this->db->prepareStatement($businessService->selectStatement($where, "", ""));
            $this->db->execute([
                "next_billing" => $today,
                "status" => "Paid",
                "paid" => 1
            ]);
            $businesses = $this->db->fetchAll();

            $response[] = 'businesses found: ' . count($businesses);
            $response[] = "----------------------------------------";
                
            $input = new \stdClass();
            foreach($businesses as $business) {
                $response[] = "Business: {$listing->business_name} - Expired on: {$listing->next_billing} - Since: {$listing->last_billing}\n";
                
                $input->id = $listing->id;
                $input->user_key_id = $listing->user_key_id;
                $input->unique_key_id = $listing->unique_key_id;

                // Downgrade expired businesses
                $downgraded = $businessService->downgradeBusiness($input);
                if ($downgraded) {
                    $response[] = "Action: Business downgraded successfully\n";
                } else {
                    $response[] = "Action: Could not downgraded business\n";
                }

                $response[] = "----------------------------------------";
            }

            return $response;
        } catch (Exception $e) {
            $response[] = $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return $response;
        }
    }

    public function checkIfExpiringListing() {
        $today = $this->utils::getDate();
        $response[] = 'today: ' . $today;
        $response[] = "----------------------------------------";

        try {
            $businessService = new ListingsService();
            $notificationsService = new NotificationsService();
            
            $expiryDays = [5, 4, 3, 2, 1];
            foreach($expiryDays as $day) {
                $expiryDate = date('Y-m-d', strtotime("+$day days", strtotime($today)));
                $where = " and 
                    date(subscriptions.next_billing) = :next_billing and 
                    subscriptions.status = :status and 
                    subscriptions.paid = :paid 
                ";
                $this->db->prepareStatement($businessService->selectStatement($where, "", ""));
                $this->db->execute([
                    "next_billing" => $expiryDate, 
                    "status" => "Paid", 
                    "paid" => 1
                ]);
                $expiringListings = $this->db->fetchAll();

                if (!empty($expiringListings)) {
                    $response[] = "Total expiring listings found: " . count($expiringListings);
                    $response[] = "Listings expiring in $expiryDays day(s) on $expiryDate: " . count($expiringListings);
                    
                    foreach ($expiringListings as $listing) {
                        $message = "Business: {$listing->business_name} | ID: {$listing->id} | Expires: {$listing->next_billing}";
                        $response[] = $message;

                        $notificationsService->sendListingReminder($listing, $message);
                    }
                } else {
                    $response[] = "No listings expiring in $expiryDate day(s)";
                }
            }
            
            $response[] = "----------------------------------------";

            return $response;
        } catch (Exception $e) {
            $response[] = $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return $response;
        }
    }

}

