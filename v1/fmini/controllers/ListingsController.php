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
            $listing = $service->get($this->input->id);
            return $this->utils::response(true, "", $listing);
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
            $listings = $service->list($this->input);
        
            return $this->utils::response(true, "", $listings);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function stats() {
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            
            $this->input->type = "all";
            $listings = $service->list($this->input);

            $all = count($listings);
            $approvals = $downgraded = $free = 0;
            foreach($listings as $listing) {
                if ($listing->status == "waiting approval") { $approvals++; }
                else if ($listing->downgraded == 1) { $downgraded++; }
                else if ($listing->free == 1) { $free++; }
            }

            $stats = [
                "all" => $all,
                "free" => $free,
                "approvals" => $approvals,
                "downgraded" => $downgraded,
            ];
            return $this->utils::response(true, "", $stats);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function listingCallLog(){
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $callLog = $service->listingCallLog($this->input);

            return $this->utils::response(true, "", $callLog);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function insert() {
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $service = new ListingsService();
            $billingService = new BillingService();

            $listing_id = $service->insert($this->input);
            $this->input->listing_id = $listing_id;

            $response = $billingService->insert($this->input);

            if ($listing_id && $this->input->type == 'free') {
                $service->calculateCommentsAverage($this->input);
            }

            return $this->utils::response(true, "Operation successful", $service->get($listing_id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function exists() {
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $service = new ListingsService();
            $response = $service->checkListingExists($this->input);

            return $this->utils::response(true, "Operation successful", $response);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function update() {
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $service = new ListingsService();
            $listing_id = $service->uopdate($this->input);

            return $this->utils::response(true, "Operation successful", $service->get($listing_id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function delete(){
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $service = new ListingsService();
            $listing_id = $service->delete($this->input);

            return $this->utils::response(true, "Operation successful", $service->get($listing_id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function search() {
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $listings = $service->searchFreeListings($this->input);
            return $this->utils::response(true, "", $listings);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function insertCallLog(){
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $listings = $service->insertCallLog($this->input);

            return $this->utils::response(true, "Call Log recorded successfully");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function insertCallLogComment(){
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new ListingsService();
            $listings = $service->insertCallLogComment($this->input);

            return $this->utils::response(true, "Call Log recorded successfully");
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
            $service->approveListing($this->input);

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
            $service->rejectListing($this->input);

            return $this->utils::response(true, "Operation successful");
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
            $service->downgradeListing($this->input);

            return $this->utils::response(true, "Operation successful", $service->get($this->input->id));
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
            $service->upgradeListing($this->input);

            return $this->utils::response(true, "Operation successful", $service->get($this->input->id));
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
            $listing_id = $service->mergeListings($this->input);

            $this->input->listing_id = $listing_id;
            $service->calculateCommentsAverage($this->input);

            return $this->utils::response(true, "Operation successful", $service->get($listing_id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}


