<?php 

class ModuleConfigModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("module_config", $db);
    }

}