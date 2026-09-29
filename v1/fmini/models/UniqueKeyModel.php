<?php 

class UniqueKeyModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("unique_key", $db);
    }

}

