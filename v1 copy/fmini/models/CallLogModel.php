<?php 

class CallLogModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("call_log", $db);
    }

}

