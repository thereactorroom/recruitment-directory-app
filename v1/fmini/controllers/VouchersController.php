<?php 

class VouchersController extends Controller {
    
    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }

    public function list(){
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $vouchersService = new VouchersService();
            return $this->utils::response(true, "", $vouchersService->list($this->input));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function active(){
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $vouchersService = new VouchersService();
            return $this->utils::response(true, "", $vouchersService->activeVouchers($this->input));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function claims(){
        if ($this->method != "get") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $vouchersService = new VouchersService();
            return $this->utils::response(true, "", $vouchersService->listClaims($this->input));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function insert(){
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $vouchersService = new VouchersService();
            $response = $vouchersService->insert($this->input);

            if (!$response['status']) {
                return $this->utils::response(false, $response['message']);
            }

            return $this->utils::response(true, $response['message']);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function update(){
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $vouchersService = new VouchersService();
            $response = $vouchersService->update($this->input);

            if (!$response['status']) {
                return $this->utils::response(false, $response['message']);
            }

            return $this->utils::response(true, $response['message'], $vouchersService->get($this->input->id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function close(){
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $vouchersService = new VouchersService();
            $response = $vouchersService->close($this->input);

            if (!$response['status']) {
                return $this->utils::response(false, $response['message']);
            }

            return $this->utils::response(true, $response['message'], $vouchersService->get($this->input->id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function delete(){
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $vouchersService = new VouchersService();
            $response = $vouchersService->delete($this->input);

            return $this->utils::response($response['status'], $response['message']);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function claim() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $vouchersService = new VouchersService();
            $response = $vouchersService->claim($this->input);

            if (!$response['status']) {
                return $this->utils::response(false, $response['message']);
            }

            $this->input->voucher_id = $response['data'];
            $listingsService = new ListingsService();
            $listingsService->payListingWithVoucher($this->input);

            return $this->utils::response(true, $response['message'], $listingsService->get($this->input->listing_id));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function search(){
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $vouchersService = new VouchersService();
            return $this->utils::response(true, "Voucher found successfully", $vouchersService->getByCode($this->input->voucher_code));
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}
