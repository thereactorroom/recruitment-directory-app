<?php 

class ListingsController extends Controller {
    
    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }

    public function get(){
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $businesses = $service->getBusinesses($this->input->id);
            return $this->utils::response(true, "", $businesses);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function list() {
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $businesses = $service->getAllBusinesses($this->input);
            return $this->utils::response(true, "", $businesses);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function list_by_status() {
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $businesses = $service->getBusinessesByStatus($this->input);
            return $this->utils::response(true, "", $businesses);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function referrals() {
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $businesses = $service->getReferralBusinesses($this->input);
            return $this->utils::response(true, "", $businesses);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }
    
    public function my_listings() {
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $listings = $service->getMyListings($this->input);
            $downgraded = $service->getMyDowngradedListings($this->input);
            return $this->utils::response(true, "", array_merge($listings, $downgraded));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    // public function my_listings() {
    //     if ($this->method != "get") {
    //         return utils::response(false, "Invalid request method");
    //     }
    //     try {
    //         if (!isset($this->input)) {
    //             return $this->utils::response(false, "Invalid request inputs");
    //         }
    //         $service = new ListingsService();
    //         $businesses = $service->getMyListings($this->input);
    //         return $this->utils::response(true, "", $businesses);
    //     } catch (Exception $e) {
    //         return $this->utils::response(false, "Error: " . $e->getMessage());
    //     }
    // }

    public function downgraded(){
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $businesses = $service->getDowngradedBusinesses($this->input);
            return $this->utils::response(true, "", $businesses);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function statuses() {
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $statuses = $service->getBusinessStatuses($this->input);
            return $this->utils::response(true, "", $statuses);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function admin_dash_stats(){
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $referrals = $service->getReferralBusinesses($this->input);
            $waiting_approvals = $service->getBusinessesByStatus($this->input);
            $businesses = $service->getAllBusinesses($this->input);
            $downgraded = $service->getDowngradedBusinesses($this->input);

            $stats = [
                "referrals" => count($referrals),
                "waiting_approvals" => count($waiting_approvals),
                "businesses" => count($businesses),
                "downgraded" => count($downgraded),
            ];
            return $this->utils::response(true, "", $stats);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function approve() {
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $service->approveBusiness($this->input);
            return $this->utils::response(true, "Operation successful");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function reject() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $service->rejectBusiness($this->input);
            return $this->utils::response(true, "Operation successful");
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
            $service = new ListingsService();
            $service->updateBusinessDetails($this->input);
            return $this->utils::response(true, "Operation successful", $service->getBusinesses($this->input->id));
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
            $service = new ListingsService();
            $response = $service->deleteBusiness($this->input);
            return $this->utils::response(true, "Operation successful", $response);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function payment() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $service = new ListingsService();
            $service->payBusiness($this->input);

            return $this->utils::response(true, "Operation successful", $service->getBusinesses($this->input->id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function reSubmit(){
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $service = new ListingsService();
            $service->reSubmitBusiness($this->input);

            return $this->utils::response(true, "Operation successful", $service->getBusinesses($this->input->id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function downgrade() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $service = new ListingsService();
            $service->downgradeBusiness($this->input);

            return $this->utils::response(true, "Operation successful", $service->getBusinesses($this->input->id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function upgrade() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $service = new ListingsService();
            $service->upgradeBusiness($this->input);

            return $this->utils::response(true, "Operation successful", $service->getBusinesses($this->input->id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function merge(){
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $service = new ListingsService();
            $business_id = $service->mergeBusinesses($this->input);

            return $this->utils::response(true, "Operation successful", $service->getBusinesses($business_id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function importDataToPreReg() {
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $traders = $service->importDataToPreReg($this->input);
            return $this->utils::response(true, "", $traders);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function copyDataToNewDevSite() {
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $service->copyDataToNewDevSite($this->input);
            return $this->utils::response(true, "Operation successful");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function printPreRegToExcel(){
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $service->printPreRegToExcel($this->input);
            return $this->utils::response(true, "Operation successful");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function checkWhatsAppNumber() {
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            $response = $this->utils::checkWhatsApp($this->input->country_code, $this->input->number);
            $message = $response ? "exists" : "does not exist";
            return $this->utils::response(true, "Whatsapp number $message", $response);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function updateWhatsAppNumber(){
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            $service = new ListingsService();
            $response = $service->updateWhatsAppNumber();
            return $this->utils::response(true, "Updated whatsapp numbers", $response);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function updateSubscriptionDates(){
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            $service = new ListingsService();
            $response = $service->updateSubscriptionDates();
            return $this->utils::response(true, "Updated subscription dates", $response);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function fixMobileAssignment() {
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            $service = new ListingsService();
            $response = $service->fixMobileAssignment();
            return $this->utils::response(true, "Updated subscription dates", $response);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}


