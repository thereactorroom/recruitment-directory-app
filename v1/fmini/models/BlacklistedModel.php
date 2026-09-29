<?php 

class BlacklistedModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("blacklisted", $db);
    }

}

