<?php 

class VoucherUsageModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("voucher_usage", $db);
    }

}