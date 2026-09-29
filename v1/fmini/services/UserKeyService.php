<?php 

class UserKeyService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function getById($id) {
        $userKeyModel = new UserKeyModel($this->db); 
        return $userKeyModel->find(["id" => $id]);
    }

    public function getUserKey($input){
        $userKeyModel = new UserKeyModel($this->db); 
        $user_key = $userKeyModel->find([
            "user_id" => $input->user_id,
            "unique_key_id" => $input->unique_key_id
        ]);
        return $user_key;
    }

    public function createUserKey($input) {
        try {
            $this->db->beginTransaction();
            $added = $this->utils::getDateTime();
            $userKeyModel = new UserKeyModel($this->db); 
            $userKeyModel->insert([
                "user_id" => $input->user_id,
                "unique_key_id" => $input->unique_key_id,
                "mobile" => $input->mobile,
                "country_code" => $input->country_code
            ]);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

}