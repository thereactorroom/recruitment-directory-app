<?php 

class BillingService extends Service {
    
    public function __construct() {
        parent::__construct();
    }



    public function insert($input) {
        try {
            $this->db->beginTransaction();
            $subscriptionModel = new SubscriptionsModel($this->db); 
            
            $subscription = $subscriptionModel->find([
                "unique_key_id" => $input->unique_key_id,
                "listing_id" => $input->listing_id,
            ]);
            
            if (!$subscription) {
                $date = $this->utils::getDateTime();
                $subscription_id = $subscriptionModel->insert([
                    "unique_key_id" => $input->unique_key_id,
                    "listing_id" => $input->listing_id,
                    "first_payment" => 0.0,
                    "recurring_payment" => 0.0,
                    "billing_cycle" => "",
                    "payment_method" => "",
                    "last_billing" => $date,
                    "next_billing" => $this->utils::incrementDateTime("+1 years", $date),
                    "status" => "not paid"
                ]);
            } else {
                $subscription_id = $subscription->id;
            }
            
            $this->db->commit();
            return $subscription_id;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    // public function update($input){
    //     try {
    //         $this->db->beginTransaction();

    //         $subscriptionModel = new SubscriptionsModel($this->db); 
    //         $subscription = $subscriptionModel->find();

    //         $date = $this->utils::getDateTime();
    //         $subscription_id = $subscriptionModel->insert([
    //             "unique_key_id" => $input->unique_key_id,
    //             "listing_id" => $input->listing_id,
    //             "first_payment" => 0.0,
    //             "recurring_payment" => 0.0,
    //             "billing_cycle" => "",
    //             "payment_method" => "",
    //             "last_billing" => $date,
    //             "next_billing" => $this->utils::incrementDateTime("+1 years", $date),
    //             "status" => "pending"
    //         ]);
    //         $this->db->commit();
    //         return $subscription_id;
    //     } catch (Exception $e) {
    //         echo $e->getMessage();
    //         if ($this->db->inTransaction()) {
    //             $this->db->rollback();
    //         }
    //         return false;
    //     }
    // }
} 

