<?php 

class listingsService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function get($id){
        $where = " and listings.id = :id";
        $this->db->prepareStatement($this->selectStatement($where, "", ""));
        $this->db->execute(["id" => $id]);
        return $this->db->fetch();
    }

    public function list($input){
        $params = [];
        $where = "";
        if ($input->type == 'all') {
            $where = " and 
                listings.unique_key_id = :unique_key_id 
            ";
            $params = [
                "unique_key_id" => $input->unique_key_id
            ];
        } else if ($input->type == 'status') {
            $where = " and 
                listings.unique_key_id = :unique_key_id and 
                listings.status = :status 
            ";
            $params = [
                "unique_key_id" => $input->unique_key_id, 
                "status" => $input->status
            ];
        } else if ($input->type == 'mine') {
            $where = " and 
                listings.unique_key_id = :unique_key_id and 
                listings.user_key_id = :user_key_id and 
                listings.free = :free
            ";
            $params = [
                "unique_key_id" => $input->unique_key_id, 
                "user_key_id" => $input->user_key_id,
                "free" => 0
            ];
        } else if ($input->type == 'free') {
            $where = " and 
                listings.unique_key_id = :unique_key_id and 
                listings.free = :free
            ";
            $params = [
                "unique_key_id" => $input->unique_key_id, 
                "free" => 1
            ];
        } else if ($input->type == 'downgraded') {
            $where = " and 
                listings.unique_key_id = :unique_key_id and 
                listings.downgraded = :downgraded
            ";
            $params = [
                "unique_key_id" => $input->unique_key_id, 
                "downgraded" => 1
            ];
        }
        $order_by = "order by listings.sort_index desc ";
        $this->db->prepareStatement($this->selectStatement($where, "", $order_by));
        $this->db->execute($params);
        return $this->db->fetchAll();
    }

    public function listingCallLog($input){
        $where = " and 
            call_log.unique_key_id = :unique_key_id and 
            call_log.listing_id = :listing_id
        ";
        $this->db->prepareStatement($this->selectCallLogStatement($where, ""));
        $this->db->execute([
            "unique_key_id" => $input->unique_key_id, 
            "listing_id" => $input->listing_id
        ]);
        $call_log = $this->db->fetchAll();
        return $call_log;
    }

    public function insert($input) {
        try {
            $this->db->beginTransaction();

            $listingsModel = new ListingsModel($this->db);
            $commentsModel = new CommentsModel($this->db);
            $listingStatusesModel = new ListingStatusesModel($this->db);

            $sort_index = $this->sortIndex($input);

            $whatsapp = '';
            $contact_code = substr($input->contact_code, 1, strlen($input->contact_code));
            if ($this->utils::checkWhatsApp($contact_code, $input->contact_number)) {
                $whatsapp = $contact_code . "" . $input->contact_number;
            }

            $payload = [
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "sort_index" => $sort_index,
                "name" => $input->name,
                "description" => $input->description,
                "contact_code" => $input->contact_code,
                "contact_number" => $input->contact_number,
                "office_number" => $input->office_number ?? "",
                "whatsapp" => $whatsapp,
                "email" => $input->email ?? "",
                "dob" => $input->dob ?? "",
                "gender" => $input->gender ?? "",
                "age" => $input->age ?? "",
                "location" => $input->location,
                "cv_path" => "",
                "logo" => "",
                "google_url" => $input->google_url ?? "",
                "website_url" => $input->website_url ?? "",
                "facebook_url" => $input->facebook_url ?? "",
                "x_url" => $input->x_url ?? "",
                "instagram_url" => $input->instagram_url ?? "",
                "status" => $input->type == 'free' ? "approved" : "draft",
                "vat_number" => $input->vat_number ?? "",
                "registration_number" => $input->registration_number ?? "",
                "free" => $input->type == 'free' ? 1 : 0,
            ];

            if ($input->source == 'free' && $input->id > 0) {
                $listing_id = $input->id;
                $payload["id"] = $input->id;
                $listingsModel->update($payload);
            } else {
                $listing_id = $listingsModel->insert($payload);
            }            

            $comment = $input->type == 'free' ? "new free listing - gets approved automatically" : "new paid listing - draft waiting for payment";
            $listingStatusesModel->insert([
                "listing_id" => $listing_id,
                "status" => $input->type == 'free' ? "approved" : "draft",
                "comment" => $comment
            ]);

            if ($input->type == 'free') {
                $commentsModel->insert([
                    "unique_key_id" => $input->unique_key_id,
                    "user_key_id" => $input->user_key_id,
                    "listing_id" => $listing_id,
                    "comment" => $input->comment,
                    "stars" => $input->stars,
                    "updated" => $this->utils::getDateTime()
                ]);
            }

            if(!empty($input->cv_path) && !$this->utils::strContains($input->cv_path, ENV_HOST)) {
                $file_url = $this->handleUploadCVLogoFile($input->cv_path, $listing_id);
                if (!empty($file_url)) {
                    $listingsModel->update([
                        "id" => $listing_id,
                        "cv_path" => $file_url,
                    ]);
                }
            }

            if (!empty($input->logo) && !$this->utils::strContains($input->logo, ENV_HOST)) {
                $file_url = $this->handleUploadCVLogoFile($input->logo, $listing_id);
                if (!empty($file_url)) {
                    $listingsModel->update([
                        "id" => $listing_id,
                        "logo" => $file_url,
                    ]);
                }
            }

            $this->db->commit();
            return $listing_id;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function uopdate($input){
        try {
            $this->db->beginTransaction();

            $listingsModel = new ListingsModel($this->db);

            $whatsapp = '';
            $contact_code = substr($input->contact_code, 1, strlen($input->contact_code));
            if ($this->utils::checkWhatsApp($contact_code, $input->contact_number)) {
                $whatsapp = $contact_code . "" . $input->contact_number;
            }
            
            $listing = $listingsModel->get($input->id);
            $listingsModel->update([
                "id" => $input->id,
                "name" => $input->name ?? $listing->name,
                "description" => $input->description ?? $listing->description,
                "contact_code" => $input->contact_code ?? $listing->contact_code,
                "contact_number" => $input->contact_number ?? $listing->contact_number,
                "office_number" => $input->office_number ?? $listing->office_number,
                "whatsapp" => $whatsapp,
                "email" => $input->email ?? $listing->email,
                "dob" => $input->dob ?? $listing->dob,
                "gender" => $input->gender ?? $listing->gender,
                "age" => $input->age ?? $listing->age,
                "location" => $input->location ?? $listing->location,
                "google_url" => $input->google_url ?? $listing->google_url,
                "website_url" => $input->website_url ?? $listing->website_url,
                "facebook_url" => $input->facebook_url ?? $listing->facebook_url,
                "x_url" => $input->x_url ?? $listing->x_url,
                "instagram_url" => $input->instagram_url ?? $listing->instagram_url,
                "vat_number" => $input->vat_number ?? $listing->vat_number,
                "registration_number" => $input->registration_number ?? $listing->registration_number,
            ]);
            
            if(!empty($input->cv_path) && !$this->utils::strContains($input->cv_path, ENV_HOST)) {
                $file_url = $this->handleUploadCVLogoFile($input->cv_path, $listing->id);
                if (!empty($file_url)) {
                    $listingsModel->update([
                        "id" => $listing->id,
                        "cv_path" => $file_url,
                    ]);
                }
            }

            if (!empty($input->logo) && !$this->utils::strContains($input->logo, ENV_HOST)) {
                $file_url = $this->handleUploadCVLogoFile($input->logo, $listing->id);
                if (!empty($file_url)) {
                    $listingsModel->update([
                        "id" => $listing->id,
                        "logo" => $file_url,
                    ]);
                }
            }

            $this->db->commit();
            return $listing->id;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function delete($input){
        try {
            $this->db->beginTransaction();
            $listingsModel = new ListingsModel($this->db); 
            $subscriptionModel = new SubscriptionsModel($this->db); 

            $date = $this->utils::getDateTime();
            $listing = $listingsModel->find(["id" => $input->id]);

            if ($listing) {
                $listingsModel->update([
                    "id" => $listing->id,
                    "status" => "eleted",
                    "deleted" => $date,
                    "deleted_by" => $input->user_key_id
                ]);

                $subscription = $subscriptionModel->find(["unique_key_id" => $input->unique_key_id, "listing_id" => $listing->id]);
                $subscriptionModel->update([
                    "id" => $listing->id,
                    "deleted" => $date,
                    "deleted_by" => $input->user_key_id
                ]);
            }

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

    public function insertCallLog($input){
        try {
            $this->db->beginTransaction();

            $listingsModel = new ListingsModel($this->db);
            $callLogModel = new CallLogModel($this->db);

            $listingsModel->update([
                "id" => $input->listing_id,
                "last_called" => $this->utils->getDateTime(),
            ]);

            $callLogModel->insert([
                "user_key_id" => $input->user_key_id,
                "unique_key_id" => $input->unique_key_id,
                "listing_id" => $input->listing_id,
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

    public function insertCallLogComment($input) {
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

    public function approveListing($input) {
        try {
            $this->db->beginTransaction();

            $listingsModel = new ListingsModel($this->db);
            $listingStatusesModel = new ListingStatusesModel($this->db);

            $listingsModel->update([
                "id" => $input->id,
                "status" => "approved"
            ]);

            $listingStatusesModel->insert([
                "listing_id" => $input->id,
                "status" => "approved"
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

    public function rejectListing($input) {
        try {
            $this->db->beginTransaction();
            
            $listingsModel = new ListingsModel($this->db);
            $listingStatusesModel = new ListingStatusesModel($this->db);

            $listingsModel->update([
                "id" => $input->id,
                "status" => "rejected"
            ]);

            $listingStatusesModel->insert([
                "listing_id" => $input->id,
                "status" => "rejected",
                "comment" => $input->comment
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

    public function downgradeListing($input) {
        try {
            $this->db->beginTransaction();
            $listingsModel = new ListingsModel($this->db); 
            $subscriptionModel = new SubscriptionsModel($this->db); 

            $date = $this->utils::getDateTime();
            $subscription = $subscriptionModel->find(["unique_key_id" => $input->unique_key_id, "listing_id" => $input->id]);

            $input->type = "free";
            $sort_index = $this->sortIndex($input);

            $listingsModel->update([
                "id" => $input->id,
                "sort_index" => $sort_index,
                "free" => 1,
                "downgraded" => 1,
                "date_downgraded" => $date
            ]);

            $subscriptionModel->update([
                "id" => $subscription->id,
                "status" => "Not Paid",
                "paid" => 0
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    } 
    
    public function upgradeListing($input){
        try {
            $this->db->beginTransaction();
            $listingsModel = new ListingsModel($this->db); 
            $subscriptionModel = new SubscriptionsModel($this->db); 
            $paymentLogsModel = new PaymentLogsModel($this->db);

            $payment_date = $this->utils::getDateTime($input->start_date);
            $subscription = $subscriptionModel->find(["unique_key_id" => $input->unique_key_id, "listing_id" => $input->id]);
            
            $sort_index = $this->sortIndex($input);

            $listingsModel->update([
                "id" => $input->id,
                "sort_index" => $sort_index,
                "free" => 0,
                "downgraded" => 0,
                "date_downgraded" => null
            ]);

            $increment = "+" . strtolower("$input->period_length months");
            $subscriptionModel->update([
                "id" => $subscription->id,
                "status" => "Paid",
                "paid" => 1,
                "payment_method" => $input->type == 'eft_cash' ? "EFT / Cash" : "Admin Allocation",
                "payment_date" => $payment_date,
                "last_billing" => $payment_date,
                "next_billing" => $this->utils::incrementDateTime($increment, $payment_date),
            ]);

            $paymentLogsModel->insert([
                'unique_key_id' => $input->unique_key_id,
                'listing_id' => $input->id,
                'subscription_id' => $subscription->id,
                'date_paid' => $payment_date,
                'amount' => $input->type == 'eft_cash' ? $input->amount : 0.0,
                'method' => $input->type == 'eft_cash' ? "EFT / Cash" : "Admin Allocation",
                'comment' => $input->type == 'eft_cash' ? "EFT / Cash" : "Admin Allocation",
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function mergeListings($input){
        try {
            $this->db->beginTransaction();
            $listingsModel = new ListingsModel($this->db);
            $commentsModel = new CommentsModel($this->db);

            $mainListing = $listingsModel->find(["id" => $input->main_listing_id]);  
            $secondListing = $listingsModel->find(["id" => $input->second_listing_id]);  

            $mainListingComments = $commentsModel->findAll(["listing_id" => $input->main_listing_id, "deleted" => ""]);
            $secondListingComments = $commentsModel->findAll(["listing_id" => $input->second_listing_id, "deleted" => ""]);

            $mainCommentUserIdList = [];
            foreach($mainListingComments as $mainComment) {
                $mainCommentUserIdList[$mainComment->id] = $mainComment->user_key_id;
            }

            // Move comments from second business to main business
            foreach($secondListingComments as $secondComment) {
                if (!in_array($secondComment->user_key_id, array_values($mainCommentUserIdList))) {
                    // not in main, move it
                    $commentsModel->update([
                        "id" => $secondComment->id,
                        "listing_id" => $mainListing->id
                    ]);
                } else {
                    // else in main, take the lastest comment
                    $mainCommentId = array_search($secondComment->user_key_id, $mainCommentUserIdList);
                    $mainComment = $commentsModel->find(["id" => $mainCommentId]);
                    if (strtotime($secondComment->added) > strtotime($mainComment->added)) {
                        $commentsModel->update([
                            "id" => $mainComment->id,
                            "comment" => $secondComment->comment,
                            "stars" => $secondComment->stars,
                            "added" => $secondComment->added,
                        ]);
                    }
                }
            }

            // Delete Merged Business
            $listingsModel->update([
                "id" => $secondListing->id,
                "deleted" => $this->utils::getDateTime(),
                "deleted_by" => $input->user_key_id
            ]);

            $this->db->commit();
            return $mainListing->id;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    } 

    public function payListing($input, $data = []) {
        try {
            $this->db->beginTransaction();
            
            $listingsModel = new ListingsModel($this->db);
            $subscriptionModel = new SubscriptionsModel($this->db); 
            $listingstatusesModel = new ListingstatusesModel($this->db);
            $paymentLogsModel = new PaymentLogsModel($this->db);

            $listing = $listingsModel->find(["id" => $input->id]);
            $payload = [
                "id" => $listing->id,
                "status" => "waiting approval",
                "free" => 0,
                "downgraded" => 0,
                "date_downgraded" => null
            ];

            if ($listing->free == 1) {
                $input->type = "paid";
                $input->unique_key_id = $listing->unique_key_id;
                $sort_index = $this->sortIndex($input);
                $payload["sort_index"] = $sort_index;
            }
            
            $listingsModel->update($payload);

            $listingstatusesModel->insert([
                "listing_id" => $listing->id,
                "status" => "waiting approval"
            ]);

            $payment_date = $this->utils::getDateTime();
            $subscriptionModel->update([
                "id" => $input->subscription_id,
                "status" => "Paid",
                "paid" => 1,
                "payment_date" => $payment_date,
            ]);

            $paymentLogsModel->insert([
                'unique_key_id' => $listing->unique_key_id,
                'listing_id' => $listing->id,
                'subscription_id' => $input->subscription_id,
                'date_paid' => $payment_date,
                'amount' => $input->amount
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

    public function payListingWithVoucher($input, $data = []) {
        try {
            $this->db->beginTransaction();
            
            $listingsModel = new ListingsModel($this->db);
            $subscriptionModel = new SubscriptionsModel($this->db); 
            $listingStatusesModel = new ListingStatusesModel($this->db);
            $paymentLogsModel = new PaymentLogsModel($this->db);
            $vouchersModel = new VouchersModel($this->db);

            $listing = $listingsModel->find(["id" => $input->listing_id]);
            $voucher = $vouchersModel->find(["id" => $input->voucher_id]);

            $payload = [
                "id" => $listing->id,
                "status" => "waiting approval",
                "free" => 0,
                "downgraded" => 0,
                "date_downgraded" => null
            ];

            if ($listing->free == 1) {
                $input->type = "paid";
                $input->unique_key_id = $listing->unique_key_id;
                $sort_index = $this->sortIndex($input);
                $payload["sort_index"] = $sort_index;
            }
            
            $listingsModel->update($payload);

            $listingStatusesModel->insert([
                "listing_id" => $listing->id,
                "status" => "waiting approval"
            ]);

            $payment_date = $this->utils::getDateTime();
            $increment = "+" . strtolower("$voucher->period_length $voucher->period_type");
            $subscription = $subscriptionModel->find(["unique_key_id" => $listing->unique_key_id, "listing_id" => $listing->id]);
            $subscriptionModel->update([
                "id" => $subscription->id,
                "status" => "Paid",
                "paid" => 1,
                "payment_method" => "Voucher",
                "payment_date" => $payment_date,
                "last_billing" => $payment_date,
                "next_billing" => $this->utils::incrementDateTime($increment, $payment_date),
            ]);

            $paymentLogsModel->insert([
                'unique_key_id' => $listing->unique_key_id,
                'listing_id' => $listing->id,
                'subscription_id' => $subscription->id,
                'date_paid' => $payment_date,
                'amount' => 0.0,
                'method' => "Voucher",
                'comment' => "Voucher Code " . $voucher->code
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

    public function checkListingExists($input){
        $listingsModel = new ListingsModel($this->db);
        $listing = $listingsModel->find([
            "unique_key_id" => $input->unique_key_id,
            "name" => $input->name,
            "contact_number" => $input->contact_number,
        ]);
        if ($listing) {
            if ((bool)$listing->downgraded) {
                return false;
            } else if ((bool)$listing->free) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function calculateCommentsAverage($input) {
        try {
            $this->db->beginTransaction();
            $listingModel = new ListingsModel($this->db); 
            $commentsModel = new CommentsModel($this->db);

            $comments = $commentsModel->findAll(["listing_id" => $input->listing_id, "deleted" => ""]);

            $stars_count = 0;
            $actual_comments = 0;
            foreach($comments as $comment) {
                $stars_count += $comment->stars;
                if ((int)$comment->stars > 0){
                    $actual_comments++;
                }
            }
            
            if ($actual_comments == 0) {
                $stars_count = 0.0;
            } else {
                $stars_count = (float)$stars_count / (float)$actual_comments;
            }

            $listingModel->update([
                "id" => $input->listing_id,
                "comments" => count($comments),
                "stars_avg" => (float)$stars_count,
            ]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function searchFreeListings($input) {
        try {
            if ($input->action == "name") {
                $s_query = "
                    select 
                        id, 
                        unique_key_id,
                        name, 
                        contact_code,
                        contact_number, 
                        description, 
                        location 
                    from listings 
                    where 
                        unique_key_id = {$input->unique_key_id} and 
                        lower(name) like '%{$input->name}%' and 
                        free = 1
                ";
            } else if ($input->action == "mobile") {
                $s_query = "
                    select 
                        id, 
                        unique_key_id,
                        name, 
                        contact_code,
                        contact_number, 
                        description, 
                        location
                    from listings 
                    where 
                        unique_key_id = {$input->unique_key_id} and 
                        (lower(name) like '%{$input->name}%' or contact_number like '%{$input->contact_number}%') and 
                        free = 1
                ";
            }

            $this->db->prepareStatement($s_query);
            $this->db->execute();

            $listings = [];
            foreach($this->db->fetchAll() as $listing) {
                $listing->source = 'free';
                $listings[] = $listing;
            }

            return $listings;
        } catch (Exception $e) {
            return false;
        }
    }

    public function selectStatement($where = "", $limit = "", $order_by = "") {
        $s_query = "
            select 
                listings.id,
                listings.unique_key_id,
                listings.user_key_id,
                user_key.user_id,
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
                listings.last_called,
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
                subscriptions.payment_date
            FROM listings
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

    public function selectCallLogStatement($where = "", $limit = "") {
        $s_query = "
            select 
                call_log.id,
                call_log.unique_key_id,
                call_log.user_key_id,
                call_log.listing_id,
                call_log.type,
                call_log.type_id,
                call_log.comment,
                call_log.added,
                user_key.user_id
            from call_log
                inner join user_key on user_key.id = call_log.user_key_id
            where 
                call_log.deleted = '' $where 
            order by call_log.added desc 
            $limit
        ";
        return $s_query;
    }

    protected function sortIndex($input) {
        $sortIndexModel = new SortIndexModel($this->db);
        $sortIndex = $sortIndexModel->find(["unique_key_id" => $input->unique_key_id]);

        if ($input->type == 'free') {
            if ($sortIndex) {
                $sort_index = (int)$sortIndex->free - 1;
                $sortIndexModel->update([
                    "id" => $sortIndex->id,
                    "free" => $sort_index
                ]);
            } else {
                $sort_index = -1;
                $sortIndexModel->insert([
                    'unique_key_id' => $input->unique_key_id,
                    'paid' => 0,
                    'free' => $sort_index
                ]);
            }
        } else {
            if ($sortIndex) {
                $sort_index = (int)$sortIndex->paid + 1;
                $sortIndexModel->update([
                    "id" => $sortIndex->id,
                    "paid" => $sort_index
                ]);
            } else {
                $sort_index = 1;
                $sortIndexModel->insert([
                    'unique_key_id' => $input->unique_key_id,
                    'paid' => $sort_index,
                    'free' => 0
                ]);
            }
        }
        return $sort_index;
    }

    protected function handleUploadCVLogoFile($file, $listing_id){
        if (preg_match('/^data:(.+);base64,(.+)$/', $file, $matches)) {
            $mimeType = $matches[1];
            $fileData = base64_decode($matches[2]);
            $extensions = [
                'application/pdf' => 'pdf',
                'image/png'       => 'png',
                'image/jpeg'      => 'jpg',
                'image/jpg'       => 'jpg',
                'image/gif'       => 'gif',
            ];
            if (isset($extensions[$mimeType])) {
                $extension = $extensions[$mimeType];
                $filename = $listing_id . '_' . uniqid() . '.' . $extension;

                $file_path = ENV_PATH . 'uploads/recruitment_directory/' . $filename;
                $file_url  = ENV_HOST . '/modules/module_dev/uploads/recruitment_directory/' . $filename;

                if (file_put_contents($file_path, $fileData) !== false) {
                    return $file_url;
                }
            }
        }
        return "";
    }

}


// subscriptions.paid DESC,
// CASE 
//     WHEN subscriptions.paid = 1 THEN subscriptions.payment_date
//     ELSE NULL 
// END DESC,
// CASE 
//     WHEN subscriptions.paid = 0 THEN listings.free 
//     ELSE NULL 
// END DESC,


