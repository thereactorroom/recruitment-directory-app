<?php 

class StatsController extends Controller {
    
    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }

    public function get(){
        if ($this->method != "get"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $service = new StatsService();
            $stats = $service->getStats($this->input);
        
            return $this->utils::response(true, "", $stats);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}
