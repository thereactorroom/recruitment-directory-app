<?php 

class RegistrationController extends Controller {
    
    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }

    public function search() {
        if ($this->method != "get"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new RegistrationService();
            // $preregs = $service->searchPreReg($this->input);
            $referrals = $service->searchReferrals($this->input);

            $results = [];
            // foreach($preregs as $item) $results[] = $item;
            foreach($referrals as $item) $results[] = $item;

            return $this->utils::response(true, "", $results);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function details() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $business_id = null;
            $service = new ListingsService();
            $subscriptionService = new SubscriptionsService();

            if ($this->input->source == 'prereg') {
                $business_id = $service->savePreRegBusinessDetails($this->input);
                $this->input->business_id = $business_id;
                if (!$subscriptionService->getBusinessNotPaidSubscription($this->input)){
                    $subscriptionService->insertSubscription($this->input);
                }
            } else if ($this->input->source == 'referral') {
                $business_id = $this->input->business_id;
                $this->input->id = $business_id;
                $service->claimReferralBusiness($this->input);
                $service->removeBusinessReferral($this->input);
            } else {
                $business_id = $service->saveNoSourceBusinessDetails($this->input);
                $this->input->business_id = $business_id;
                if (!$subscriptionService->getBusinessNotPaidSubscription($this->input)){
                    $subscriptionService->insertSubscription($this->input);
                }
            }

            return $this->utils::response(true, "", $service->getBusinesses($business_id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function checkingBusinessExists(){
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $service = new ListingsService();
            $response = $service->checkingBusinessExists($this->input);
            return $this->utils::response(true, "", $response);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function channels(){

    }

    public function contact() {

    }

}

