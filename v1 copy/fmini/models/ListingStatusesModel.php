<?php 

class ListingStatusesModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("listing_statuses", $db);
    }

}