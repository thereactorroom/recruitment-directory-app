<?php 

class UniqueKeyController extends Controller {
    
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

            $service = new UniqueKeyService();
            $unique_key = $service->getUniqueKey($this->input);
            if (!$unique_key) {
                $service->createUniqueKey($this->input);
            }
            $unique_key = $service->getUniqueKey($this->input);

            $this->input->unique_key_id = $unique_key->id;
            $service = new UserKeyService();
            $user_key = $service->getUserKey($this->input);
            if (!$user_key) {
                $service->createUserKey($this->input);
            }
            $user_key = $service->getUserKey($this->input);

            return $this->utils::response(true, "", ["unique_key" => $unique_key, "user_key" => $user_key]);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}

