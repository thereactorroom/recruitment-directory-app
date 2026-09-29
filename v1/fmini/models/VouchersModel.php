<?php 

class VouchersModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("vouchers", $db);
    }

    public function generateVoucherCode(int $length = 10): string {
        $characters = 'ABCDEFGHJKLMNOPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $code;
    }

    public function voucherExists(int $unique_key_id, string $code) {
        $voucher = $this->find([
            "unique_key_id" => $unique_key_id, 
            "code" => $code,
            "deleted" => ""
        ]);
        return $voucher ? true : false;
    }

}