<?php 

class SubscriptionsService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function getBusinessNotPaidSubscription($input) {
        $subscriptionModel = new SubscriptionsModel($this->db); 
        $subscription = $subscriptionModel->find([
            "unique_key_id" => $input->unique_key_id,
            "business_id" => $input->business_id,
            "status" => "Not Paid"
        ]);
        return $subscription;
    }
    
    public function insertSubscription($input) {
        try {
            $this->db->beginTransaction();
            $subscriptionModel = new SubscriptionsModel($this->db); 
            $date = $this->utils::getDateTime();
            $subscription_id = $subscriptionModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "business_id" => $input->business_id,
                "first_payment" => 1200.0,
                "recurring_payment" => 1200.0,
                "billing_cycle" => "Annual",
                "payment_method" => "PayFast",
                "last_billing" => $date,
                "next_billing" => $this->utils::incrementDateTime("+1 years", $date),
                "status" => "Not Paid"
            ]);
            $this->db->commit();
            return $subscription_id;
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function insertReferralSubscription($input) {
        try {
            $this->db->beginTransaction();
            $subscriptionModel = new SubscriptionsModel($this->db); 
            $date = $this->utils::getDateTime();
            $subscription_id = $subscriptionModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "business_id" => $input->business_id,
                "first_payment" => 1200.0,
                "recurring_payment" => 1200.0,
                "billing_cycle" => "Annual",
                "payment_method" => "PayFast",
                "last_billing" => $date,
                "next_billing" => $this->utils::incrementDateTime("+1 years", $date),
                "status" => "Not Paid"
            ]);
            $this->db->commit();
            return $subscription_id;
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

}

