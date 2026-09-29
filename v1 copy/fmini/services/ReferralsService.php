<?php 

class ReferralsService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function getReferrals($input) {
        $referralsModel = new ReferralsModel($this->db); 
        $referrals = $referralsModel->findAll(["unique_key_id" => $input->unique_key_id, 'deleted' => '']);
        // print_r($referrals);
        return $referrals;
    }

    public function getReferralByBusinessId($business_id) {
        $referralsModel = new ReferralsModel($this->db); 
        $referral = $referralsModel->find(["business_id" => $business_id]);
        return $referral;
    }

    public function getReferralCallLog($input) {
        $where = " and 
            call_log.unique_key_id = :unique_key_id and 
            call_log.referral_id = :referral_id
        ";
        $this->db->prepareStatement($this->selectCallLogStatement($where, ""));
        $this->db->execute([
            "unique_key_id" => $input->unique_key_id, 
            "referral_id" => $input->referral_id
        ]);
        $call_log = $this->db->fetchAll();
        return $call_log;
        
    }

    public function insertReferral($input) {
        try {
            $this->db->beginTransaction();
            
            $ListingsModel = new ListingsModel($this->db); 
            $commentsModel = new CommentsModel($this->db);
            $favoritesModel = new FavoritesModel($this->db);
            $referralsModel = new ReferralsModel($this->db); 
            $sortIndexModel = new SortIndexModel($this->db);

            $whatsapp = '';
            if ($this->utils::checkWhatsApp("+27", $input->contact_number)) {
                $whatsapp = $input->contact_number;
            }

            $sortIndex = $sortIndexModel->find(["unique_key_id" =>  $input->unique_key_id]);
            if ($sortIndex) {
                $sort_index = (int)$sortIndex->referrals - 1;
                $sortIndexModel->update([
                    "id" => $sortIndex->id,
                    "referrals" => $sort_index
                ]);
            } else {
                $sort_index = 1;
                $sortIndexModel->insert([
                    'unique_key_id' => $input->unique_key_id,
                    'businesses' => 0,
                    'referrals' => 1
                ]);
            }
            
            $business_id = $ListingsModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "sort_index" => $sort_index,
                "business_name" => $input->business_name,
                "contact_number" => $input->contact_number,
                "office_number" => $input->contact_number,
                "description" => $input->description,
                'whatsapp' => $whatsapp,
                "referral" => 1,
                "status" => "Approved"
            ]);

            $commentsModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "business_id" => $business_id,
                "comment" => $input->comment,
                "stars" => $input->stars,
                "updated" => $this->utils::getDateTime()
            ]);

            // if (!(bool)$input->admin) {
            //     $favoritesModel->insert([
            //         "unique_key_id" => $input->unique_key_id,
            //         "user_key_id" => $input->user_key_id,
            //         "business_id" => $business_id
            //     ]);
            // }
            
            $referralsModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "business_id" => $business_id
            ]);

            $this->db->commit();
            return $business_id;
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function updateReferral($input) {
        try {
            $this->db->beginTransaction();

            $ListingsModel = new ListingsModel($this->db); 
            $commentsModel = new CommentsModel($this->db);

            $whatsapp = '';
            if ($this->utils::checkWhatsApp("+27", $input->contact_number)) {
                $whatsapp = $input->contact_number;
            }

            $business = $ListingsModel->get($input->business_id);
            $ListingsModel->update([
                "id" => $listing->id,
                "business_name" => $input->business_name,
                "contact_number" => $input->contact_number,
                "office_number" => $input->contact_number,
                "description" => $input->description,
                "whatsapp" => $whatsapp
            ]);

            $comment = $commentsModel->find([
                "unique_key_id" => $listing->unique_key_id,
                "user_key_id" => $listing->user_key_id,
                "business_id" => $listing->id,
            ]);
            $commentsModel->update([
                "id" => $comment->id,
                "comment" => $input->comment,
                "stars" => $input->stars,
                "updated" => $this->utils::getDateTime(),
                "deleted" => "",
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function deleteReferral($input) {
        try {
            $this->db->beginTransaction();

            $ListingsModel = new ListingsModel($this->db); 
            $referralModel = new ReferralsModel($this->db); 
            
            $ListingsModel->update([
                "id" => $input->business_id,
                "deleted" => $this->utils::getDateTime(),
                "deleted_by" => $input->user_key_id
            ]);

            $referralModel->update([
                "id" => $input->referral_id,
                "deleted" => $this->utils::getDateTime(),
                "deleted_by" => $input->user_key_id
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function insertReferralCallLog($input) {
        try {
            $this->db->beginTransaction();

            $referralModel = new ReferralsModel($this->db); 
            $callLogModel = new CallLogModel($this->db);

            $referralModel->update([
                "id" => $input->referral_id,
                "last_called" => $this->utils->getDateTime(),
            ]);

            $callLogModel->insert([
                "user_key_id" => $input->user_key_id,
                "unique_key_id" => $input->unique_key_id,
                "referral_id" => $input->referral_id,
                "comment" => ""
            ]);

            $this->db->commit();

            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function insertReferralCallLogComment($input) {
        try {
            $this->db->beginTransaction();

            $callLogModel = new CallLogModel($this->db);
            $callLogModel->update([
                "id" => $input->id,
                "comment" => $input->comment,
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function checkWhatsAppNumber($number) {
        try {
            $idInstance = "7103866037";
            $apiTokenInstance = "3d9130fdede34cc983d1d8ced0f19bc9602874c14b83426cb7";
            $url = "https://api.green-api.com/waInstance$idInstance/checkWhatsapp/$apiTokenInstance";
            $this->curl->post($url, json_encode(["phoneNumber" => $number]));            
            if (!empty($this->curl->json()->existsWhatsapp)) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function selectStatement($where = "", $limit = "") {
        $s_query = "
            select 
                referrals.id,
                referrals.unique_key_id,
                referrals.user_key_id,
                referrals.business_id,
                referrals.last_called,
                referrals.closed,
                referrals.closed_by,
                referrals.closed_date,
                referrals.added,
            from referrals
                inner join businesses on businesses.id = referrals.business_id
            where 
                referrals.deleted = '' $where 
            order by referrals.added desc 
            $limit
        ";
        return $s_query;
    }

    public function selectCallLogStatement($where = "", $limit = "") {
        $s_query = "
            select 
                call_log.id,
                call_log.unique_key_id,
                call_log.user_key_id,
                call_log.referral_id,
                call_log.type,
                call_log.type_id,
                call_log.comment,
                call_log.added,
                user_key.user_id
            from call_log
                inner join referrals on referrals.id = call_log.referral_id
                inner join user_key on user_key.id = call_log.user_key_id
            where 
                call_log.deleted = '' $where 
            order by call_log.added desc 
            $limit
        ";
        return $s_query;
    }


}

