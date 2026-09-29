<?php 

class UniqueKeyService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function getById($id) {
        $uniqueKeyModel = new UniqueKeyModel($this->db); 
        return $uniqueKeyModel->find(["id" => $id]);
    }

    public function getUniqueKey($input) {
        $uniqueKeyModel = new UniqueKeyModel($this->db); 
        $unique_key = $uniqueKeyModel->find(["community_id" => $input->community_id, "deleted" => ""]);
        if ($unique_key) {
            $content_ids = explode(",", $unique_key->content_id);
            if(in_array($input->content_id, $content_ids)) {
                return $unique_key;
            }
        }
        return null;
    }

    public function createUniqueKey($input) {
        try {
            $this->db->beginTransaction();
            $uniqueKeyModel = new UniqueKeyModel($this->db); 
            $unique_key = $uniqueKeyModel->find(["community_id" => $input->community_id, "deleted" => ""]);
            if ($unique_key) {
                $uniqueKeyModel->update([
                    "id" => $unique_key->id,
                    "content_id" => $unique_key->content_id . "," . $input->content_id
                ]);
            } else {
                $uniqueKeyModel->insert([
                    "community_id" => $input->community_id,
                    "content_id" => $input->content_id
                ]);
            }
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