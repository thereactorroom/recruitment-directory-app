<?php 

class PaymentLogsModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("payment_logs", $db);
    }

}