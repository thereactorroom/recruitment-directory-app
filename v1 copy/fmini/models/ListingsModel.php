<?php 

class ListingsModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("listings", $db);
    }

}