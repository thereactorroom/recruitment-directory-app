<?php 

class VouchersService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function get($id) {
        $vouchersModel = new VouchersModel($this->db);
        $voucher = $vouchersModel->find(["id" => $id, "deleted" => ""]);
        $voucher->status = (bool)$voucher->is_active ? "LIVE" : "ENDED";
        return $voucher;
    }

    public function getByCode($code) {
        $vouchersModel = new VouchersModel($this->db);
        $voucher = $vouchersModel->find(["code" => $code, "unique_key_id" => $input->unique_key_id, "deleted" => ""]);
        $voucher->status = (bool)$voucher->is_active ? "LIVE" : "ENDED";
        return $voucher;
    }

    public function list($input) {
        $where = " and vouchers.unique_key_id = :unique_key_id";
        $order_by = "order by vouchers.added desc ";

        $this->db->prepareStatement($this->selectStatement($where, "", $order_by));
        $this->db->execute(["unique_key_id" => $input->unique_key_id]);
        
        $vouchers = $this->db->fetchAll();

        for($i = 0; $i < count($vouchers); $i++) {
            $vouchers[$i]->status = (bool)$vouchers[$i]->is_active ? "LIVE" : "ENDED";
        }
        return $vouchers;
    }

    public function activeVouchers($input) {
        $vouchersModel = new VouchersModel($this->db);
        $vouchers = $vouchersModel->findAll([
            "unique_key_id" => $input->unique_key_id, 
            "is_active" => 1,
            "deleted" => ""
        ]);
        $results = [];
        for($i = 0; $i < count($vouchers); $i++) {
            if ($vouchers[$i]->available > 0) {
                $vouchers[$i]->status = "LIVE";
                $results[] = $vouchers[$i];
            }
        }
        return $results;
    }

    public function listClaims($input) {
        $where = " and voucher_usage.unique_key_id = :unique_key_id
            and voucher_usage.voucher_id = :voucher_id";
        $this->db->prepareStatement($this->selectClaimsStatement($where));
        $this->db->execute(["unique_key_id" => $input->unique_key_id, "voucher_id" => $input->voucher_id]);
        return $this->db->fetchAll();
    }

    public function insert($input) {
        try {
            $this->db->beginTransaction();
            $vouchersModel = new VouchersModel($this->db); 

            $startDate = new DateTime($input->start_date);
            $endDate = new DateTime($input->end_date);

            // Compare the objects directly
            if ($startDate > $endDate) {
                $this->db->commit();
                return [
                    'status' => false,
                    'message' => "Voucher StartDate cannot be greater that EndDate."
                ];
            }
            
            $voucher = $vouchersModel->find([
                "unique_key_id" => $input->unique_key_id, 
                "code" => $input->code,
                "is_active" => 1,
                "deleted" => ""
            ]);

            if ($voucher) {
                $this->db->commit();
                return [
                    'status' => false,
                    'message' => "Voucher with code {$input->code} already exists and active. Please use a different code."
                ];
            }

            $month = " Month";
            if ((int)$input->period_length > 1) {
                $month = " Months";
            }

            $voucher_id = $vouchersModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "code" => $input->code,
                "capacity" => $input->capacity,
                "used" => 0,
                "available" => $input->capacity,
                "period_type" => $month,
                "period_length" => $input->period_length,
                "description" => $input->period_length . $month,
                "end_date" => $input->end_date,
                "start_date" => $this->utils::getDate(),
            ]);

            $this->db->commit();
            return [
                'status' => true,
                'message' => "Voucher created successfully.",
                'data' => $voucher_id
            ];
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return [
                'status' => false,
                'message' => "Error: " . $e->getMessage(),
            ];
        }
    }

    public function update($input) {
        try {
            $this->db->beginTransaction();
            
            $vouchersModel = new VouchersModel($this->db); 
            $voucher = $vouchersModel->find(["id" => $input->id]);

            if ((int)$input->capacity < (int)$voucher->used) {
                $this->db->commit();
                return [
                    'status' => false,
                    'message' => "Voucher capacity cannot be less than used vouchers."
                ];
            }

            $startDate = new DateTime($input->start_date);
            $endDate = new DateTime($input->end_date);

            // Compare the objects directly
            if ($startDate > $endDate) {
                $this->db->commit();
                return [
                    'status' => false,
                    'message' => "Voucher StartDate cannot be greater that EndDate."
                ];
            }
            
            $closed_date = "";
            $closed_by = "";
            $is_active = $voucher->is_active;
            $available = $input->capacity - $voucher->used;
            if ($available <= 0) {
                $is_active = 0;
                $closed_date = $this->utils::getDate();
                $closed_by = $input->user_key_id ?? $voucher->user_key_id;
            }

            $vouchersModel->update([
                "id" => $voucher->id,
                "capacity" => $input->capacity ?? $voucher->capacity,
                "available" => $available,
                "is_active" => $is_active,
                "start_date" => $input->start_date,
                "end_date" => $input->end_date,
                "closed_date" => $closed_date,
                "closed_by" => $closed_by,
                "updated" => $this->utils::getDateTime(),
            ]);

            $this->db->commit();
            return [
                'status' => true,
                'message' => "Voucher capacity updated successfully.",
                'data' => $voucher->id
            ];
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return [
                'status' => false,
                'message' => "Error: " . $e->getMessage(),
            ];
        }
    }

    public function close($input) {
        try {
            $this->db->beginTransaction();
            
            $vouchersModel = new VouchersModel($this->db); 
            $vouchersModel->update([
                "id" => $input->id,
                "is_active" => 0,
                "closed_date" => $this->utils::getDate(),
                "closed_by" => $input->user_key_id
            ]);

            $this->db->commit();
            return [
                'status' => true,
                'message' => "Voucher closed successfully.",
            ];
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return [
                'status' => false,
                'message' => "Error: " . $e->getMessage(),
            ];
        }
    }

    public function delete($input) {
        try {
            $this->db->beginTransaction();
            
            $vouchersModel = new VouchersModel($this->db); 
            $voucher = $vouchersModel->get($input->id);

            if (!$voucher) {
                return [
                    'status' => false,
                    'message' => "Voucher not found.",
                ];
            }

            $vouchersModel->delete($input->id);

            $this->db->commit();
            return [
                'status' => true,
                'message' => "Voucher deleted successfully.",
            ];
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return [
                'status' => false,
                'message' => "Error: " . $e->getMessage(),
            ];
        }
    }

    public function claim($input) {
        try {
            $this->db->beginTransaction();
            
            $vouchersModel = new VouchersModel($this->db); 
            $voucherUsageModel = new VoucherUsageModel($this->db);

            $voucher = $vouchersModel->find([
                "unique_key_id" => $input->unique_key_id, 
                "code" => $input->voucher_code, 
                "deleted" => ""
            ]);

            if (!$voucher) {
                $this->db->commit();
                return [
                    'status' => false,
                    'message' => 'Invalid voucher code.'
                ];
            }

            if (!(bool)$voucher->is_active) {
                $this->db->commit();
                return [
                    'status' => false,
                    'message' => 'Sorry, voucher campaign has ended.'
                ];
            }

            $voucherClaimed = $voucherUsageModel->find([
                "unique_key_id" => $input->unique_key_id,
                "voucher_id" => $voucher->id,
                "listing_id" => $input->listing_id
            ]);

            if ($voucherClaimed) {
                $this->db->commit();
                return [
                    'status' => false,
                    'message' => 'You have already claim this voucher for your business.'
                ];
            }

            if ($voucher->available <= 0) {
                $vouchersModel->update([
                    "id" => $voucher->id,
                    "is_active" => 0,
                    "closed_date" => $this->utils::getDate(),
                    "closed_by" => $input->user_key_id ?? $voucher->user_key_id,
                    "updated" => $this->utils::getDateTime(),
                ]);
                $this->db->commit();
                return [
                    'status' => false,
                    'message' => 'Ther are no vouchers to claim.'
                ];
            }

            $used = $voucher->used + 1;
            $available = $voucher->available - 1;

            $closed_date = "";
            $closed_by = "";
            $is_active = 0;
            if ($available <= 0) {
                $is_active = 0;
                $closed_date = $this->utils::getDate();
                $closed_by = $input->user_key_id ?? $voucher->user_key_id;
            }

            $vouchersModel->update([
                "id" => $voucher->id,
                "used" => $used,
                "available" => $available,
                "closed_date" => $closed_date,
                "closed_by" => $closed_by,
                "updated" => $this->utils::getDateTime(),
            ]);
            $voucherUsageModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "voucher_id" => $voucher->id,
                "listing_id" => $input->listing_id
            ]);

            $this->db->commit();
            return [
                'status' => true,
                'message' => "You have claimed {$voucher->period_length} {$voucher->period_type} free voucher successfully.",
                'data' => $voucher->id
            ];
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function selectStatement($where, $limit = ""){
        $s_query = "
            select 
                vouchers.id,
                vouchers.unique_key_id,
                vouchers.code,
                vouchers.capacity,
                vouchers.used,
                vouchers.available,
                vouchers.period_type,
                vouchers.period_length,
                vouchers.description,
                vouchers.start_date,
                vouchers.end_date,
                vouchers.is_active,
                vouchers.closed_date,
                vouchers.closed_by,
                vouchers.added
            from vouchers
            where 
                vouchers.deleted = '' $where 
            order by vouchers.added desc 
            $limit
        ";
        return $s_query;
    }

    public function selectClaimsStatement($where = "", $limit = "", $order_by = "") {
        $s_query = "
            select 
                listings.id,
                listings.unique_key_id,
                listings.user_key_id,
                user_key.user_id,
                voucher_usage.id as voucher_usage_id,
                unique_key.community_id,
                unique_key.content_id,
                listings.sort_index,
                listings.name,
                listings.description,
                listings.contact_code,
                listings.contact_number,
                listings.office_number,
                listings.whatsapp,
                listings.email,
                listings.dob,
                listings.gender,
                listings.location,
                listings.cv_path,
                listings.logo,
                listings.whatsapp,
                listings.google_url,
                listings.website_url,
                listings.facebook_url,
                listings.x_url,
                listings.instagram_url,
                listings.vat_number,
                listings.registration_number,
                listings.comments,
                CAST(listings.stars_avg AS DECIMAL(10,1)) AS stars_avg,
                listings.free,
                listings.status,
                listings.downgraded,
                date(listings.date_downgraded) as date_downgraded,
                listings.added,

                subscriptions.id as subscription_id,
                subscriptions.first_payment,
                subscriptions.recurring_payment,
                subscriptions.billing_cycle,
                subscriptions.payment_method,
                subscriptions.last_billing,
                subscriptions.next_billing,
                subscriptions.status as subscription_status,
                subscriptions.paid,
                subscriptions.payment_date,

                date(voucher_usage.added) as claim_date

            FROM voucher_usage
            INNER JOIN listings ON listings.id = voucher_usage.listing_id
            INNER JOIN subscriptions ON subscriptions.listing_id = listings.id
            INNER JOIN user_key ON user_key.id = listings.user_key_id
            INNER JOIN unique_key ON unique_key.id = listings.unique_key_id
            WHERE 
                listings.deleted = '' $where
            ORDER BY
                listings.sort_index DESC;
            $limit
        ";
        return $s_query;
    }

    protected function available ($voucher){
        return max(
            0,
            $voucher->capacity - $voucher->used
        );
    }

}

