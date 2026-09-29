<?php 

class ReferralsModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("referrals", $db);
    }

}