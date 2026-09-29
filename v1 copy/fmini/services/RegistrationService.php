<?php 

class RegistrationService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function searchPreReg($input) {
        try {

            if ($input->action == "name") {
                $s_query = "
                    select 
                        id, 
                        unique_key_id,
                        business_name, 
                        mobile, 
                        email, 
                        description, 
                        logo 
                    from pre_reg 
                    where 
                        unique_key_id = {$input->unique_key_id} and 
                        lower(business_name) like '%{$input->business_name}%' and 
                        claimed = 0
                ";
            } else if ($input->action == "mobile") {
                $s_query = "
                    select 
                        id, 
                        unique_key_id,
                        business_name, 
                        mobile, 
                        email, 
                        description, 
                        logo 
                    from pre_reg 
                    where 
                        unique_key_id = {$input->unique_key_id} and 
                        (lower(business_name) like '%{$input->business_name}%' or mobile like '%{$input->mobile}%') and 
                        claimed = 0
                ";
            }

            $this->db->prepareStatement($s_query);
            $this->db->execute();

            $traders = [];
            foreach($this->db->fetchAll() as $trader) {
                $trader->source = 'prereg';
                $traders[] = $trader;
            }

            return $traders;
        } catch (Exception $e) {
            return false;
        }
    }

    public function searchReferrals($input) {
        try {

            if ($input->action == "name") {
                $s_query = "
                    select 
                        businesses.id, 
                        businesses.unique_key_id, 
                        businesses.business_name, 
                        businesses.contact_number as mobile, 
                        businesses.email, 
                        businesses.description, 
                        businesses.logo 
                    from referrals 
                    inner join businesses on businesses.id = referrals.business_id
                    where 
                        referrals.unique_key_id = {$input->unique_key_id} and 
                        lower(businesses.business_name) like '%{$input->business_name}%' and 
                        closed = 0
                ";
            } else if ($input->action == "mobile") {
                $s_query = "
                    select 
                        businesses.id, 
                        businesses.unique_key_id, 
                        businesses.business_name, 
                        businesses.contact_number as mobile, 
                        businesses.email, 
                        businesses.description, 
                        businesses.logo 
                    from referrals 
                    inner join businesses on businesses.id = referrals.business_id
                    where 
                        referrals.unique_key_id = {$input->unique_key_id} and 
                        lower(businesses.business_name) like '%{$input->business_name}%' and 
                        businesses.contact_number like '%{$input->mobile}%' and 
                        closed = 0
                ";
            }

            $this->db->prepareStatement($s_query);
            $this->db->execute();

            $traders = [];
            foreach($this->db->fetchAll() as $trader) {
                $trader->source = 'referral';
                $traders[] = $trader;
            }

            return $traders;
        } catch (Exception $e) {
            return false;
        }
    }

}

