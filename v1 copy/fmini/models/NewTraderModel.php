<?php 

class NewTraderModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("sd_new_traders", $db);
    }

}