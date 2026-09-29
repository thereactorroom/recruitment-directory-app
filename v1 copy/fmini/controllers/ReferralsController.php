<?php 

class ReferralsController extends Controller {
    
    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }

    public function list() {
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ReferralsService();
            $referrals = $service->getReferrals($this->input);
            return $this->utils::response(true, "", $referrals);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function comment() {
        if ($this->method != "get"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $commentsModel = new CommentsModel($this->db);
            $comment = $commentsModel->find([
                "unique_key_id" => $this->input->unique_key_id,
                "user_key_id" => $this->input->user_key_id,
                "business_id" => $this->input->business_id,
                "deleted" => ""
            ]);
            return $this->utils::response(true, "", $comment);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function call_log() {
        if ($this->method != "get"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $uniqueKeyService = new UniqueKeyService();
            $service = new ReferralsService();

            $call_logs = $service->getReferralCallLog($this->input);
            $unique_key = $uniqueKeyService->getById($this->input->unique_key_id);

            $results = [];
            foreach ($call_logs as $call_log){
                $member = $this->utils::getMember($call_log->user_id, $unique_key->community_id);
                $call_log->name = $member->name;
                $call_log->surname = $member->surname;
                $call_log->mobile = $member->mobile;
                $call_log->picture = !empty($member->picture) ? $this->utils::$member_pic_url . $member->picture : "";
                $results[] = $call_log;
            }
            
            return $this->utils::response(true, "", $results);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function insert() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $ListingsService = new ListingsService();
            $referralService = new ReferralsService();
            $subscriptionsService = new SubscriptionsService();
            
            $business_id = $referralService->insertReferral($this->input);
            if ($business_id && $business_id > 0) {
                $this->input->business_id = $business_id;
                $ListingsService->calculateCommentsAverage($this->input);
            }

            $subscriptionsService->insertReferralSubscription($this->input);

            $business = $ListingsService->getBusinesses($business_id);
            return $this->utils::response(true, "Referral added successfully", $business);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function update() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $ListingsService = new ListingsService();
            $referralService = new ReferralsService();

            $response = $referralService->updateReferral($this->input);
            if ($response) {
                $ListingsService->calculateCommentsAverage($this->input);
            }

            $business = $ListingsService->getBusinessById($this->input->business_id);
            return $this->utils::response(true, "Referral updated successfully", $business);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function delete() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $service = new ReferralsService();
            $service->deleteReferral($this->input);

            return $this->utils::response(true, "Referral deleted");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function add_call_log() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $service = new ReferralsService();
            $service->insertReferralCallLog($this->input);

            return $this->utils::response(true, "Call Log recorded successfully");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function add_call_log_comment() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $service = new ReferralsService();
            $service->insertReferralCallLogComment($this->input);

            return $this->utils::response(true, "Call Log recorded successfully");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}




