<?php 

class MembersController extends Controller {
    
    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }

    public function blacklisted() {
        if ($this->method != "get"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $model = new BlacklistedModel();
            $results = $model->findAll(["unique_key_id" => $this->input->unique_key_id]);
            return $this->utils::response(true, "", $results);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function blacklist() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            $model = new BlacklistedModel();
            $model->insert([
                "unique_key_id" => $this->input->unique_key_id,
                "user_key_id" => $this->input->user_key_id,
                "member_id" => $this->input->member_id,
            ]);
            return $this->utils::response(true, "member blacklisted successfully");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function unblacklist() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            $model = new BlacklistedModel();
            $blacklist = $model->find([
                "unique_key_id" => $this->input->unique_key_id,
                "user_key_id" => $this->input->user_key_id,
                "member_id" => $this->input->member_id,
            ]);
            $model->delete($blacklist->id);
            return $this->utils::response(true, "member blacklisted successfully");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }


}