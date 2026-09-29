<?php 

class UserKeyModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("user_key", $db);
    }

}