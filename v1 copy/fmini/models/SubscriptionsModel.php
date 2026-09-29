<?php 

class SubscriptionsModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("subscriptions", $db);
    }

}