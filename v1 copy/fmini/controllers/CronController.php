<?php 

class CronController extends Controller {
    
    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }

    public function checkExpiredAndDowngrade() {
        if ($this->method != "post"){
            return $this->utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $cronService = new CronService();
            $response = $cronService->checkExpiredAndDowngrade();

            return $this->utils::response(true, "v2 - Daily cron executed successfully", $response);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function manuallyCheckExpiredAndDowngrade(){
        if ($this->method != "post"){
            return $this->utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $cronService = new CronService();
            $response = $cronService->checkExpiredAndDowngrade();

            return $this->utils::response(true, "v2 - Daily cron executed successfully", $response);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function checkIfExpiringListing() {
        if ($this->method != "post"){
            return $this->utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $cronService = new CronService();
            $response = $cronService->checkIfExpiringListing();

            return $this->utils::response(true, "v2 - Expiring listings checked successfully", $response);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}