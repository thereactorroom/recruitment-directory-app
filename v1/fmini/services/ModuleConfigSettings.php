<?php

class ModuleConfigSettings extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function get($input) {
        $moduleConfigModel = new ModuleConfigModel($this->db);
        return $moduleConfigModel->find(['unique_key_id' => $input->unique_key_id]);
    }

    public function save($input) {
        try {
            $this->db->beginTransaction();

            $moduleConfigModel = new ModuleConfigModel($this->db);
            $moduleConfig = $moduleConfigModel->find(['unique_key_id' => $input->unique_key_id]);

            if ($moduleConfig) {
                $moduleConfigModel->update([
                    'id' => $moduleConfig->id,
                    'app_title' => $input->app_title,
                    'base44_specials_url' => $input->base44_specials_url
                ]);
            } else {
                $moduleConfigModel->insert([
                    'unique_key_id' => $input->unique_key_id,
                    'app_title' => $input->app_title,
                    'base44_specials_url' => $input->base44_specials_url
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

}