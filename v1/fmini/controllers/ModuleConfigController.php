<?php 

class ModuleConfigController extends Controller {
    
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
            
            $service = new ModuleConfigSettings();
            $config = $service->get($this->input);
        
            return $this->utils::response(true, "", $config);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function save(){
        if ($this->method != "post") {
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            
            $service = new ModuleConfigSettings();
            $results = $service->save($this->input);
        
            return $this->utils::response(
                $results, 
                $results ? "Config saved successfully" : "Failed to save configs",
                $results ? $service->get($this->input) : null
            );
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}
