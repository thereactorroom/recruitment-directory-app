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
        $where = " and 
            listings.unique_key_id = :unique_key_id and 
            listings.status = :status 
        ";
        $order_by = "order by listings.sort_index desc ";
        $this->db->prepareStatement($this->selectStatement($where, "", $order_by));
        $this->db->execute([
            "unique_key_id" => $input->unique_key_id, "status" => $input->status
        ]);
        return $this->db->fetchAll();
    }

    public function getAllBusinesses($input){
        $where = " and listings.unique_key_id = :unique_key_id";
        $order_by = "order by listings.sort_index desc ";
        $this->db->prepareStatement($this->selectStatement($where, "", $order_by));
        $this->db->execute(["unique_key_id" => $input->unique_key_id]);
        return $this->db->fetchAll();
    }

    public function getReferralBusinesses($input) {
        $where = " and 
            listings.unique_key_id = :unique_key_id and 
            listings.referral = :referral
        ";
        $this->db->prepareStatement($this->selectReferralsStatement($where));
        $this->db->execute([
            "unique_key_id" => $input->unique_key_id, "referral" => $input->referral
        ]);
        return $this->db->fetchAll();
    }

    public function getMylistings($input) {
        $where = "";
        if (isset($input->community_id) && isset($input->user_id)) {
            $where = " and 
                unique_key.community_id = :community_id and 
                user_key.user_id = :user_id
            ";
            $parameters = ["community_id" => $input->community_id, "user_id" => $input->user_id];
        } else if (isset($input->unique_key_id) && isset($input->user_key_id)) {
            $where = " and 
                listings.unique_key_id = :unique_key_id and 
                listings.user_key_id = :user_key_id
            ";
            $parameters = ["unique_key_id" => $input->unique_key_id, "user_key_id" => $input->user_key_id];
        }

        $where .= " and listings.referral = :referral ";
        $parameters["referral"] = 0;

        $order_by = "order by subscriptions.payment_date desc ";
        $this->db->prepareStatement($this->selectStatement($where, "", $order_by));
        $this->db->execute($parameters);

        $businesses = $this->db->fetchAll();
        
        $results = [];
        $listingstatusesModel = new listingstatusesModel($this->db);
        foreach($businesses as $business) {
            $statuses = $listingstatusesModel->findAll(['business_id' => $listing->id]);
            $last_item = end($statuses);
            $listing->status_comment = $last_item->comment ?? "";
            // $views = $this->curl->get(ENV_HOST . "/modules/module_dev/module_analytics/impressions/search?period=weekly,monthly&_event=listing_opened&event_id=$listing->id", true);
            // if ($views->status == true) {
            //     $listing->weekly_views = count($views->data->weekly ?? []);
            //     $listing->monthly_views = count($views->data->monthly ?? []);
            // }
            $results[] = $business;
        }
        return $results;
    }

    public function getMyDowngradedlistings($input) {
        $where = "";
        if (isset($input->community_id) && isset($input->user_id)) {
            $where = " and 
                unique_key.community_id = :community_id and 
                user_key.user_id = :user_id
            ";
            $parameters = ["community_id" => $input->community_id, "user_id" => $input->user_id];
        } else if (isset($input->unique_key_id) && isset($input->user_key_id)) {
            $where = " and 
                listings.unique_key_id = :unique_key_id and 
                listings.user_key_id = :user_key_id
            ";
            $parameters = ["unique_key_id" => $input->unique_key_id, "user_key_id" => $input->user_key_id];
        }

        $where .= " and listings.downgraded = :downgraded ";
        $parameters["downgraded"] = 1;

        $order_by = "order by subscriptions.payment_date desc ";
        $this->db->prepareStatement($this->selectStatement($where, "", $order_by));
        $this->db->execute($parameters);

        $businesses = $this->db->fetchAll();
                
        $results = [];
        $listingstatusesModel = new listingstatusesModel($this->db);
        foreach($businesses as $business) {
            $statuses = $listingstatusesModel->findAll(['business_id' => $listing->id]);
            $last_item = end($statuses);
            $listing->status_comment = $last_item->comment ?? "";
            // $views = $this->curl->get(ENV_HOST . "/modules/module_dev/module_analytics/impressions/search?period=weekly,monthly&_event=listing_opened&event_id=$listing->id", true);
            // if ($views->status == true) {
            //     $listing->weekly_views = count($views->data->weekly ?? []);
            //     $listing->monthly_views = count($views->data->monthly ?? []);
            // }
            $results[] = $business;
        }
        return $results;
    }

    public function getDowngradedBusinesses($input) {
        $where = " and 
            listings.unique_key_id = :unique_key_id and 
            listings.downgraded = :downgraded
        ";
        $order_by = "order by subscriptions.payment_date desc ";
        $this->db->prepareStatement($this->selectReferralsStatement($where, "", $order_by));
        $this->db->execute([ "unique_key_id" => $input->unique_key_id, "downgraded" => 1 ]);

        $businesses = $this->db->fetchAll();
        
        $results = [];
        $listingstatusesModel = new listingstatusesModel($this->db);
        foreach($businesses as $business) {
            $statuses = $listingstatusesModel->findAll(['business_id' => $listing->id]);
            $last_item = end($statuses);
            $listing->status_comment = $last_item->comment ?? "";
            // $views = $this->curl->get(ENV_HOST . "/modules/module_dev/module_analytics/impressions/search?period=weekly,monthly&_event=listing_opened&event_id=$listing->id", true);
            // if ($views->status == true) {
            //     $listing->weekly_views = count($views->data->weekly ?? []);
            //     $listing->monthly_views = count($views->data->monthly ?? []);
            // }
            $results[] = $business;
        }
        return $results;
    }

    public function getBusinessStatuses($input) {
        $listingstatusesModel = new listingstatusesModel();
        if (isset($input->status)) {
            return $listingstatusesModel->find([
                "business_id" => $input->business_id,
                "status" => $input->status
            ]);
        } else {
            return $listingstatusesModel->findAll([
                "business_id" => $input->business_id,
            ]);
        }
    }

    public function insertFree($input) {
        try {
            $this->db->beginTransaction();

            $listingsModel = new listingsModel($this->db);
            $sortIndexModel = new SortIndexModel($this->db);
            $commentsModel = new CommentsModel($this->db);
            $listingstatusesModel = new listingstatusesModel($this->db);

            $sortIndex = $sortIndexModel->find(["unique_key_id" => $input->unique_key_id]);
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

            $whatsapp = '';
            if ($this->utils::checkWhatsApp($input->contact_code, $input->contact_number)) {
                $whatsapp = $input->contact_number;
            }
            
            $listing_id = $listingsModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "sort_index" => $sort_index,
                "name" => $input->name,
                "description" => $input->description,
                "contact_code" => $input->contact_code,
                "contact_number" => $input->contact_number,
                "office_number" => $input->contact_number ?? "",
                "whatsapp" => $whatsapp,
                "email" => $input->email ?? "",
                "dob" => $input->dob ?? "",
                "gender" => $input->gender ?? "",
                "location" => $input->location,
                "cv_path" => "",
                "logo" => "",
                "google_url" => $input->google_url ?? "",
                "website_url" => $input->website_url ?? "",
                "facebook_url" => $input->facebook_url ?? "",
                "x_url" => $input->x_url ?? "",
                "instagram_url" => $input->instagram_url ?? "",
                "status" => "approved",
                "vat_number" => $input->vat_number ?? "",
                "registration_number" => $input->registration_number ?? "",
                "free" => 1,
            ]);

            $listingstatusesModel->insert([
                "listing_id" => $listing_id,
                "status" => "approved",
                "comment" => "new free listing - gets approved automatically"
            ]);

            $commentsModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "listing_id" => $business_id,
                "comment" => $input->comment,
                "stars" => $input->stars,
                "updated" => $this->utils::getDateTime()
            ]);
            
            // if ($input->cv_path) {
            //     $parts = explode(";base64,", $input->cv_path);
            //     $imageparts = explode("application-/", $parts[0]);
            //     $imagetype = $imageparts[1];
            //     $imagebase64 = base64_decode($parts[1]);

            //     $filename = $listing_id . "_" . uniqid() . ".$imagetype";
            //     $file_path = ENV_PATH . "/images/recruitment_directory/" . $filename;
            //     $file_url = ENV_HOST . "/modules/module_dev/images/recruitment_directory/" . $filename;
            //     $success = file_put_contents($file_path, $imagebase64);
                
            //     if ($success) {
            //         $listingsModel->update([
            //             "id" => $listing_id,
            //             "cv_path" => $file_url,
            //         ]);
            //     }
            // }

            // if ($input->logo) {
            //     $parts = explode(";base64,", $input->logo);
            //     $imageparts = explode("image/", $parts[0]);
            //     $imagetype = $imageparts[1];
            //     $imagebase64 = base64_decode($parts[1]);

            //     $filename = $listing_id . "_" . uniqid() . ".$imagetype";
            //     $file_path = ENV_PATH . "/images/recruitment_directory/" . $filename;
            //     $file_url = ENV_HOST . "/modules/module_dev/images/recruitment_directory/" . $filename;
            //     $success = file_put_contents($file_path, $imagebase64);
                
            //     if ($success) {
            //         $listingsModel->update([
            //             "id" => $listing_id,
            //             "logo" => $file_url,
            //         ]);
            //     }
            // }

            $this->db->commit();
            return $listing_id;
        } catch (Exception $e) {
            echo $e->getMessage();
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function savePreRegBusinessDetails($input){
        try {
            $this->db->beginTransaction();
            $businessModel = new listingsModel($this->db);
            $sectionModel = new SectionsModel($this->db);
            $sortIndexModel = new SortIndexModel($this->db);
            $listingstatusesModel = new listingstatusesModel($this->db);
            $preRegModel = new PreRegModel($this->db);

            $whatsapp = '';
            if ($this->utils::checkWhatsApp("+27", $input->contact_number)) {
                $whatsapp = $input->contact_number;
            }

            $business = $businessModel->find([
                "unique_key_id" => $input->unique_key_id,
                "pre_reg_id" => $input->business_id
            ]);
            if ($business) {
                $business_id = $listing->id;
                $businessModel->update([
                    "id" => $listing->id,
                    "business_name" => $input->business_name,
                    "vat_number" => $input->vat_number,
                    "registration_number" => $input->registration_number,
                    "country_code" => $input->country_code,
                    "contact_number" => $input->contact_number,
                    "office_number" => $input->office_number,
                    "email" => $input->email,
                    "person_name" => $input->person_name,
                    "person_surname" => $input->person_surname,
                    "description" => $input->description,
                    'whatsapp' => $whatsapp,
                    "google_url" => $input->google_url,
                    "website_url" => $input->website_url,
                    "facebook_url" => $input->facebook_url,
                    "x_url" => $input->x_url,
                    "instagram_url" => $input->instagram_url,
                    "status" => "Draft"
                ]);
            } else {
                $preReg = $preRegModel->find(["id" => $input->business_id]);
                $preRegModel->update([
                    "id" => $preReg->id,
                    "claimed" => 1,
                    "claimed_by" => $input->user_key_id,
                    "claimed_date" => $this->utils::getDateTime()
                ]);
                
                $sortIndex = $sortIndexModel->find(["unique_key_id" =>  $input->unique_key_id]);
                if ($sortIndex) {
                    $sort_index = (int)$sortIndex->businesses + 1;
                    $sortIndexModel->update([
                        "id" => $sortIndex->id,
                        "businesses" => $sort_index
                    ]);
                } else {
                    $sort_index = 1;
                    $sortIndexModel->insert([
                        'unique_key_id' => $input->unique_key_id,
                        'businesses' => 1,
                        'referrals' => 0
                    ]);
                }

                $business_id = $businessModel->insert([
                    "unique_key_id" => $input->unique_key_id,
                    "user_key_id" => $input->user_key_id,
                    "pre_reg_id" => $input->business_id,
                    "sort_index" => $sort_index,
                    "business_name" => $input->business_name,
                    "vat_number" => $input->vat_number,
                    "registration_number" => $input->registration_number,
                    "country_code" => $input->country_code,
                    "contact_number" => $input->contact_number,
                    "office_number" => $input->office_number,
                    "email" => $input->email,
                    "person_name" => $input->person_name,
                    "person_surname" => $input->person_surname,
                    "description" => $input->description,
                    'whatsapp' => $whatsapp,
                    "google_url" => $input->google_url,
                    "website_url" => $input->website_url,
                    "facebook_url" => $input->facebook_url,
                    "x_url" => $input->x_url,
                    "instagram_url" => $input->instagram_url,
                    "status" => "Draft"
                ]);
                $sectionModel->insert([
                    "business_id" => $business_id,
                    "details" => 1,
                ]);
                $listingstatusesModel->insert([
                    "business_id" => $business_id,
                    "status" => "Draft",
                ]);
            }

            if ($input->logo) {
                $parts = explode(";base64,", $input->logo);
                $imageparts = explode("image/", $parts[0]);
                $imagetype = $imageparts[1];
                $imagebase64 = base64_decode($parts[1]);

                $filename = $business_id . "_" . uniqid() . ".$imagetype";
                $file_path = ENV_PATH . "/images/business_listings/" . $filename;
                $success = file_put_contents($file_path, $imagebase64);
                
                if ($success) {
                    $businessModel->update([
                        "id" => $business_id,
                        "logo" => ENV_HOST . "/modules/module_dev/images/business_listings/" . $filename,
                    ]);
                }
            }

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

    public function saveNoSourceBusinessDetails($input){
        try {
            $this->db->beginTransaction();
            $businessModel = new listingsModel($this->db);
            $sectionModel = new SectionsModel($this->db);
            $sortIndexModel = new SortIndexModel($this->db);
            $listingstatusesModel = new listingstatusesModel($this->db);

            $sortIndex = $sortIndexModel->find(["unique_key_id" => $input->unique_key_id]);
            $sort_index = (int)$sortIndex->businesses + 1;
            $sortIndexModel->update([
                "id" => $sortIndex->id,
                "businesses" => $sort_index
            ]);

            $whatsapp = '';
            if ($this->utils::checkWhatsApp("+27", $input->contact_number)) {
                $whatsapp = $input->contact_number;
            }

            $business_id = $businessModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "pre_reg_id" => 0,
                "sort_index" => $sort_index,
                "business_name" => $input->business_name,
                "vat_number" => $input->vat_number,
                "registration_number" => $input->registration_number,
                "country_code" => $input->country_code,
                "contact_number" => $input->contact_number,
                "office_number" => $input->office_number,
                "email" => $input->email,
                "person_name" => $input->person_name,
                "person_surname" => $input->person_surname,
                "description" => $input->description,
                'whatsapp' => $whatsapp,
                "google_url" => $input->google_url,
                "website_url" => $input->website_url,
                "facebook_url" => $input->facebook_url,
                "x_url" => $input->x_url,
                "instagram_url" => $input->instagram_url,
                "status" => "Draft"
            ]);
            $sectionModel->insert([
                "business_id" => $business_id,
                "details" => 1,
            ]);
            $listingstatusesModel->insert([
                "business_id" => $business_id,
                "status" => "Draft",
            ]);
            

            if ($input->logo) {
                $parts = explode(";base64,", $input->logo);
                $imageparts = explode("image/", $parts[0]);
                $imagetype = $imageparts[1];
                $imagebase64 = base64_decode($parts[1]);

                $filename = $business_id . "_" . uniqid() . ".$imagetype";
                $file_path = ENV_PATH . "/images/business_listings/" . $filename;
                $success = file_put_contents($file_path, $imagebase64);
                
                if ($success) {
                    $businessModel->update([
                        "id" => $business_id,
                        "logo" => ENV_HOST . "/modules/module_dev/images/business_listings/" . $filename,
                    ]);
                }
            }

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

    public function updateBusinessDetails($input) {
        try {
            $this->db->beginTransaction();
            $businessModel = new listingsModel($this->db);

            $whatsapp = '';
            if ($this->utils::checkWhatsApp("+27", $input->contact_number)) {
                $whatsapp = $input->contact_number;
            }

            $businessModel->update([
                "id" => $input->id,
                "business_name" => $input->business_name,
                "vat_number" => $input->vat_number,
                "registration_number" => $input->registration_number,
                "country_code" => $input->country_code,
                "contact_number" => $input->contact_number,
                "office_number" => $input->office_number,
                "email" => $input->email,
                "person_name" => $input->person_name,
                "person_surname" => $input->person_surname,
                "description" => $input->description,
                'whatsapp' => $whatsapp,
                "google_url" => $input->google_url,
                "website_url" => $input->website_url,
                "facebook_url" => $input->facebook_url,
                "x_url" => $input->x_url,
                "instagram_url" => $input->instagram_url,
            ]);

            if (isset($input->logo) && $this->utils->strContains($input->logo, ";base64,")) {
                $parts = explode(";base64,", $input->logo);
                $imageparts = explode("image/", $parts[0]);
                $imagetype = $imageparts[1];
                $imagebase64 = base64_decode($parts[1]);

                $filename = $input->id . "_" . uniqid() . ".$imagetype";
                $file_path = ENV_PATH . "/images/business_listings/" . $filename;
                $success = file_put_contents($file_path, $imagebase64);

                if ($success) {
                    $businessModel->update([
                        "id" => $input->id,
                        "logo" => ENV_HOST . "/modules/module_dev/images/business_listings/" . $filename,
                    ]);
                }
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

    public function claimReferralBusiness($input) {
        try {
            $this->db->beginTransaction();
            $businessModel = new listingsModel($this->db);
            $sectionModel = new SectionsModel($this->db);
            $listingstatusesModel = new listingstatusesModel($this->db);

            $whatsapp = '';
            if ($this->utils::checkWhatsApp("+27", $input->contact_number)) {
                $whatsapp = $input->contact_number;
            }

            $business_id = $input->id;
            $businessModel->update([
                "id" => $input->id,
                "user_key_id" => $input->user_key_id,
                "business_name" => $input->business_name,
                "vat_number" => $input->vat_number,
                "registration_number" => $input->registration_number,
                "country_code" => $input->country_code,
                "contact_number" => $input->contact_number,
                "office_number" => $input->office_number,
                "email" => $input->email,
                "person_name" => $input->person_name,
                "person_surname" => $input->person_surname,
                "description" => $input->description,
                'whatsapp' => $whatsapp,
                "google_url" => $input->google_url,
                "website_url" => $input->website_url,
                "facebook_url" => $input->facebook_url,
                "x_url" => $input->x_url,
                "instagram_url" => $input->instagram_url,
                "status" => "Draft"
            ]);

            $listingstatusesModel->insert([
                "business_id" => $business_id,
                "status" => "Draft",
            ]);

            if (isset($input->logo) && $this->utils->strContains($input->logo, ";base64,")) {
                $parts = explode(";base64,", $input->logo);
                $imageparts = explode("image/", $parts[0]);
                $imagetype = $imageparts[1];
                $imagebase64 = base64_decode($parts[1]);

                $filename = $input->id . "_" . uniqid() . ".$imagetype";
                $file_path = ENV_PATH . "/images/business_listings/" . $filename;
                $success = file_put_contents($file_path, $imagebase64);

                if ($success) {
                    $businessModel->update([
                        "id" => $input->id,
                        "logo" => ENV_HOST . "/modules/module_dev/images/business_listings/" . $filename,
                    ]);
                }
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

    public function deleteBusiness($input){
        try {
            $this->db->beginTransaction();
            $listingsModel = new listingsModel($this->db); 
            $sortIndexModel = new SortIndexModel($this->db);
            $referralsModel = new ReferralsModel($this->db);
            $subscriptionModel = new SubscriptionsModel($this->db); 

            $date = $this->utils::getDateTime();
            $business = $listingsModel->find(["id" => $input->id]);
            $referral = $referralsModel->find(["unique_key_id" => $input->unique_key_id, "business_id" => $listing->id]);
            $subscription = $subscriptionModel->find(["unique_key_id" => $input->unique_key_id, "business_id" => $listing->id]);

            if ($referral) {
                $referralsModel->update([
                    "id" => $referral->id,
                    "closed" => 0,
                    "closed_by" => 0,
                    "closed_date" => '',
                ]);
            } else {
                $referralsModel->insert([
                    "unique_key_id" => $input->unique_key_id,
                    "user_key_id" => $input->user_key_id,
                    "business_id" => $listing->id
                ]);
            }

            $sortIndex = $sortIndexModel->find(["unique_key_id" =>  $input->unique_key_id]);
            $sort_index = (int)$sortIndex->referrals - 1;
            $sortIndexModel->update([
                "id" => $sortIndex->id,
                "referrals" => $sort_index
            ]);

            if (isset($input->action) && $input->action == 'full') {
                $listingsModel->update([
                    "id" => $listing->id,
                    "sort_index" => $sort_index,
                    "referral" => 1,
                    "status" => "Approved",
                    "deleted" => $date,
                    "deleted_by" => $input->user_key_id
                ]);
            } else {
                $listingsModel->update([
                    "id" => $listing->id,
                    "sort_index" => $sort_index,
                    "referral" => 1,
                    "status" => "Approved"
                ]);
            }

            $subscriptionModel->update([
                "id" => $subscription->id,
                "last_billing" => $date,
                "next_billing" => $this->utils::incrementDateTime("+1 years", $date),
                "status" => "Not Paid",
                "paid" => 0
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

    public function removeBusinessReferral($input) {
        try {
            $this->db->beginTransaction();
            $businessModel = new listingsModel($this->db);
            $sortIndexModel = new SortIndexModel($this->db);
            $referralsModel = new ReferralsModel($this->db);

            $sortIndex = $sortIndexModel->find(["unique_key_id" => $input->unique_key_id]);
            $sort_index = (int)$sortIndex->businesses + 1;
            $sortIndexModel->update([
                "id" => $sortIndex->id,
                "businesses" => $sort_index
            ]);

            $businessModel->update([
                "id" => $input->id,
                "sort_index" => $sort_index,
                "referral" => 0,
            ]);

            $referral = $referralsModel->find(["unique_key_id" => $input->unique_key_id, "business_id" => $input->id]);
            $referralsModel->update([
                "id" => $referral->id,
                "closed" => 1,
                "closed_by" => $input->user_key_id,
                "closed_date" => $this->utils->getDateTime(),
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

    public function checkingBusinessExists($input){
        $businessModel = new listingsModel($this->db);
        $business = $businessModel->find([
            "unique_key_id" => $input->unique_key_id,
            "business_name" => $input->business_name,
            "contact_number" => $input->contact_number,
        ]);
        if ($business) {
            if ((bool)$listing->downgraded) {
                return false;
            } else if ((bool)$listing->referral) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function approveBusiness($input) {
        try {
            $this->db->beginTransaction();

            $businessModel = new listingsModel($this->db);
            $listingstatusesModel = new listingstatusesModel($this->db);

            $businessModel->update([
                "id" => $input->id,
                "status" => "Approved"
            ]);

            $listingstatusesModel->insert([
                "business_id" => $input->id,
                "status" => "Approved"
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

    public function rejectBusiness($input) {
        try {
            $this->db->beginTransaction();
            
            $businessModel = new listingsModel($this->db);
            $listingstatusesModel = new listingstatusesModel($this->db);

            $businessModel->update([
                "id" => $input->id,
                "status" => "Rejected"
            ]);

            $listingstatusesModel->insert([
                "business_id" => $input->id,
                "status" => "Rejected",
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

    public function payBusiness($input, $data = []) {
        try {
            $this->db->beginTransaction();
            
            $businessModel = new listingsModel($this->db);
            $subscriptionModel = new SubscriptionsModel($this->db); 
            $listingstatusesModel = new listingstatusesModel($this->db);
            $paymentLogsModel = new PaymentLogsModel($this->db);
            $sortIndexModel = new SortIndexModel($this->db);
            $referralsModel = new ReferralsModel($this->db);

            $business = $businessModel->find(["id" => $input->id]);

            $sortIndex = $sortIndexModel->find(["unique_key_id" =>  $listing->unique_key_id]);
            $sort_index = (int)$sortIndex->businesses + 1;
            $sortIndexModel->update([
                "id" => $sortIndex->id,
                "businesses" => $sort_index
            ]);

            $businessModel->update([
                "id" => $listing->id,
                "status" => "Waiting Approval",
                "sort_index" => $sort_index,
                "referral" => 0,
                "downgraded" => 0,
                "date_downgraded" => null
            ]);

            $listingstatusesModel->insert([
                "business_id" => $input->id,
                "status" => "Waiting Approval"
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
                'business_id' => $listing->id,
                'subscription_id' => $input->subscription_id,
                'date_paid' => $payment_date,
                'amount' => $input->amount
            ]);

            $referral = $referralsModel->find(["unique_key_id" => $listing->unique_key_id, "business_id" => $listing->id]);
            if ($referral) {
                $referralsModel->update([
                    "id" => $referral->id,
                    "closed" => 0,
                    "closed_by" => 0,
                    "closed_date" => '',
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

    public function reSubmitBusiness($input) {
        try {
            $this->db->beginTransaction();
            
            $businessModel = new listingsModel($this->db);
            $listingstatusesModel = new listingstatusesModel($this->db);

            $businessModel->update([
                "id" => $input->id,
                "status" => "Waiting Approval"
            ]);

            $listingstatusesModel->insert([
                "business_id" => $input->id,
                "status" => "Waiting Approval"
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

    public function downgradeBusiness($input) {
        try {
            $this->db->beginTransaction();
            $listingsModel = new listingsModel($this->db); 
            $sortIndexModel = new SortIndexModel($this->db);
            $referralsModel = new ReferralsModel($this->db);
            $subscriptionModel = new SubscriptionsModel($this->db); 

            $date = $this->utils::getDateTime();
            $business = $listingsModel->find(["id" => $input->id]);
            $referral = $referralsModel->find(["unique_key_id" => $input->unique_key_id, "business_id" => $listing->id]);
            $subscription = $subscriptionModel->find(["unique_key_id" => $input->unique_key_id, "business_id" => $listing->id]);
            
            if ($referral) {
                $referralsModel->update([
                    "id" => $referral->id,
                    "closed" => 0,
                    "closed_by" => 0,
                    "closed_date" => '',
                ]);
            } else {
                $referralsModel->insert([
                    "unique_key_id" => $input->unique_key_id,
                    "user_key_id" => $input->user_key_id,
                    "business_id" => $listing->id
                ]);
            }

            $sortIndex = $sortIndexModel->find(["unique_key_id" =>  $input->unique_key_id]);
            $sort_index = (int)$sortIndex->referrals - 1;
            $sortIndexModel->update([
                "id" => $sortIndex->id,
                "referrals" => $sort_index
            ]);

            $listingsModel->update([
                "id" => $listing->id,
                "sort_index" => $sort_index,
                "referral" => 1,
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
    
    public function upgradeBusiness($input){
        try {
            $this->db->beginTransaction();
            $listingsModel = new listingsModel($this->db); 
            $sortIndexModel = new SortIndexModel($this->db);
            $referralsModel = new ReferralsModel($this->db);
            $subscriptionModel = new SubscriptionsModel($this->db); 

            $payment_date = $this->utils::getDateTime();

            $business = $listingsModel->find(["id" => $input->id]);
            $referral = $referralsModel->find(["unique_key_id" => $input->unique_key_id, "business_id" => $listing->id]);
            $subscription = $subscriptionModel->find(["unique_key_id" => $input->unique_key_id, "business_id" => $listing->id]);

            $referralsModel->update([
                "id" => $referral->id,
                "closed" => 1,
                "closed_by" => $input->user_key_id,
                "closed_date" => $payment_date,
            ]);

            $sortIndex = $sortIndexModel->find(["unique_key_id" =>  $input->unique_key_id]);
            $sort_index = (int)$sortIndex->businesses + 1;
            $sortIndexModel->update([
                "id" => $sortIndex->id,
                "businesses" => $sort_index
            ]);

            $listingsModel->update([
                "id" => $listing->id,
                "sort_index" => $sort_index,
                "referral" => 0,
                "downgraded" => 0,
                "date_downgraded" => null
            ]);

            $increment = "";
            if ($subscription->billing_cycle == "Monthly") {
                $increment = "+30 days";
            } else if ($subscription->billing_cycle == "Quarterly") {
                $increment = "+90 days";
            } else if ($subscription->billing_cycle == "Bi-Annual") {
                $increment = "+6 months";
            } else if ($subscription->billing_cycle == "Annual") {
                $increment = "+1 years";
            } else if ($subscription->billing_cycle == "Test Payment") {
                $increment = "+30 days";
            }

            echo $payment_date . "\n" . $increment . "\n" . $subscription->billing_cycle;
            die();
            
            $subscriptionModel->update([
                "id" => $subscription->id,
                "last_billing" => $payment_date,
                "next_billing" => $this->utils::incrementDateTime($increment, $payment_date),
                "status" => "Paid",
                "paid" => 1
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

    public function calculateCommentsAverage($input){
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

    public function mergeBusinesses($input){
        try {
            $this->db->beginTransaction();
            $businessModel = new listingsModel($this->db); 
            $commentsModel = new CommentsModel($this->db);

            $main_business = $businessModel->find(["id" => $input->main_business_id]);  
            $second_business = $businessModel->find(["id" => $input->second_business_id]);  
            $main_business_comments = $commentsModel->findAll(["business_id" => $input->main_business_id, "deleted" => ""]);
            $second_business_comments = $commentsModel->findAll(["business_id" => $input->second_business_id, "deleted" => ""]);

            $main_comments_user_ids = [];
            foreach($main_business_comments as $main_comment) {
                $main_comments_user_ids[$main_comment->id] = $main_comment->user_key_id;
            }

            // Move comments from second business to main business
            foreach($second_business_comments as $second_comment) {
                if (!in_array($second_comment->user_key_id, array_values($main_comments_user_ids))) {
                    // not in main, move it
                    $commentsModel->update([
                        "id" => $second_comment->id,
                        "business_id" => $main_listing->id
                    ]);
                } else {
                    // else in main, take the lastest comment
                    $main_comment_id = array_search($second_comment->user_key_id, $main_comments_user_ids);
                    $main_comment = $commentsModel->find(["id" => $main_comment_id]);
                    if (strtotime($second_comment->added) > strtotime($main_comment->added)) {
                        $commentsModel->update([
                            "id" => $main_comment->id,
                            "comment" => $second_comment->comment,
                            "stars" => $second_comment->stars,
                            "added" => $second_comment->added,
                        ]);
                    }
                }
            }

            // Delete Merged Business
            $businessModel->update([
                "id" => $second_listing->id,
                "deleted" => $this->utils::getDateTime(),
                "deleted_by" => $input->user_key_id
            ]);

            $this->db->commit();
            return $main_listing->id;
        } catch (Exception $e) {
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

    public function updateWhatsAppNumber() {

        $listingsModel = new listingsModel();
        $businesses = $listingsModel->findAll([
            'downgraded' => 1,
            'deleted' => ''
        ]);
        // print_r(count($businesses));
        $date = $this->utils::getDateTime();
        foreach($businesses as $business) {
            $listingsModel->update([
                'id' => $listing->id,
                "date_downgraded" => $date
            ]);

            // if (!empty($listing->whatsapp)) { continue; }
            // $whatsapp = '';
            // if ($this->utils::checkWhatsApp("+27", $listing->contact_number)) {
            //     $listingsModel->update([
            //         'id' => $listing->id,
            //         'whatsapp' => $listing->contact_number
            //     ]);
            // }
        }
    }

    public function importDataToPreReg($input) {
        try {
            $query = "https://www.4traders.co.za/api/trader/textsearch.php?devID=$input->dev_id&phrase=";
            $results = $this->curl->get($query, true);
            if (!isset($results->records)) {
                return false;
            }

            $insertList = [];
            foreach ($results->records as $record) {
                $insertList[] = [
                    "unique_key_id" => 1,
                    "source_id" => $record->traderID,
                    "trader_name" => $record->tradename,
                    "mobile" => $record->telc ?? "",
                    "email" => $record->email ?? "",
                    "description" => $record->descr ?? "",
                ];
            }

            $this->db->beginTransaction();
            $preRegModel = new PreRegModel($this->db);
            $preRegModel->insertBulk($insertList);
            $this->db->commit();

            return true;
        } catch (Exception $e) {
            print_r($e->getMessage());
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function copyDataToNewDevSite($input) {
        try {
            $this->db->beginTransaction();

            $preRegModel = new PreRegModel($this->db);

            $preRegItems = $preRegModel->findAll(["unique_key_id" => $input->copy_from_unique_key_id]);

            $insertList = [];
            foreach($preRegItems as $preReg) {
                $insertList[] = [
                    "unique_key_id" => $input->copy_to_unique_key_id,
                    "source_id" => $preReg->source_id,
                    "business_name" => $preReg->business_name,
                    "mobile" => $preReg->mobile,
                    "email" => $preReg->email,
                    "description" => $preReg->description,
                    "logo" => $preReg->logo,
                    "claimed" => 0,
                    "claimed_by" => 0,
                    "claimed_date" => "",
                ];
            }
            $preRegModel->insertBulk($insertList);

            $this->db->commit();

            return true;
        } catch (Exception $e) {
            print_r($e->getMessage());
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function fixMobileAssignment() {
        $userKeyModel = new UserKeyModel($this->db); 
        $listingsModel = new listingsModel($this->db);

        $businesses = $listingsModel->findAll([
            'unique_key_id' => 2,
            'user_key_id' => 5
        ]);
        // print_r(count($businesses));
        $numbers = [];
        $keys = [];
        foreach($businesses as $business) {

            $mobile = str_replace(' ', '', $listing->contact_number);
            if ($this->utils::strContains($mobile, '+27')) { 
                $mobile = str_replace('+27', '0', $mobile);
            }
            
            $user_key = $userKeyModel->find(['mobile' => $mobile]);

            if ($user_key) {
                $numbers[$listing->id] = "$listing->contact_number = $mobile ; $listing->user_key_id = $user_key->id";
                $listingsModel->update([
                    'id' => $listing->id,
                    'user_key_id' => $user_key->id
                ]);
            }
            
        }
        echo '<pre/>';
        print_r($numbers);
    }

    public function updateSubscriptionDates(){
        $subscriptions = [
            [
                "date" => "2025-11-17",
                "business_name" => "Moto Mate",
                "subscription" => "Annual",
                "payment_id" => "93-105",
                "amount" => "990.00",
                "expiry" => "2026-11-16",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2025-11-23",
                "business_name" => "Deckwood",
                "subscription" => "Annual",
                "payment_id" => "80-92",
                "amount" => "990.00",
                "expiry" => "2026-11-22",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2025-11-26",
                "business_name" => "Sensible Solar",
                "subscription" => "Annual",
                "payment_id" => null,
                "amount" => "990.00",
                "expiry" => "2026-11-25",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2025-12-06",
                "business_name" => "Air-Elec",
                "subscription" => "Annual",
                "payment_id" => "720-732",
                "amount" => "990.00",
                "expiry" => "2026-12-05",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2025-12-19",
                "business_name" => "Poppy",
                "subscription" => "Monthly",
                "payment_id" => "726-738",
                "amount" => "99.00",
                "expiry" => "2026-01-18",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2025-12-24",
                "business_name" => "Karibu",
                "subscription" => "Annual",
                "payment_id" => "127-139",
                "amount" => "990.00",
                "expiry" => "2026-12-23",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2025-12-26",
                "business_name" => "Keith Isaac",
                "subscription" => "Annual",
                "payment_id" => "730-742",
                "amount" => "990.00",
                "expiry" => "2026-12-25",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-04",
                "business_name" => "North Coast Projects",
                "subscription" => "Annual",
                "payment_id" => "732-744",
                "amount" => "990.00",
                "expiry" => "2027-01-03",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-05",
                "business_name" => "Employment Law 360",
                "subscription" => "Annual",
                "payment_id" => "731-743",
                "amount" => "990.00",
                "expiry" => "2027-01-04",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-05",
                "business_name" => "Bonte Electrical",
                "subscription" => "Annual",
                "payment_id" => "136-148",
                "amount" => "990.00",
                "expiry" => "2027-01-04",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-05",
                "business_name" => "Rock",
                "subscription" => "Annual",
                "payment_id" => "733-745",
                "amount" => "990.00",
                "expiry" => "2027-01-04",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-05",
                "business_name" => "H Borehole ",
                "subscription" => "Annual",
                "payment_id" => "734-746",
                "amount" => "990.00",
                "expiry" => "2027-01-04",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-05",
                "business_name" => "Way of Light",
                "subscription" => "Annual",
                "payment_id" => "735-747",
                "amount" => "990.00",
                "expiry" => "2027-01-04",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-05",
                "business_name" => "Midway Family ",
                "subscription" => "Annual",
                "payment_id" => "736-748",
                "amount" => "990.00",
                "expiry" => "2027-01-04",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-05",
                "business_name" => "Rust and Coat",
                "subscription" => "Annual",
                "payment_id" => "738-750",
                "amount" => "990.00",
                "expiry" => "2027-01-04",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-05",
                "business_name" => "Little Readers",
                "subscription" => "Annual",
                "payment_id" => "711-723",
                "amount" => "990.00",
                "expiry" => "2027-01-04",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-06",
                "business_name" => "Awnmaster PTY",
                "subscription" => "Annual",
                "payment_id" => "740-752",
                "amount" => "990.00",
                "expiry" => "2027-01-04",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-06",
                "business_name" => "4 Real",
                "subscription" => "Monthly",
                "payment_id" => "741-753",
                "amount" => "99.00",
                "expiry" => "2026-02-05",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-06",
                "business_name" => "F G Electrical",
                "subscription" => "Monthly",
                "payment_id" => "742-754",
                "amount" => "99.00",
                "expiry" => "2026-02-05",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-07",
                "business_name" => "All Pools",
                "subscription" => "Monthly",
                "payment_id" => "743-755",
                "amount" => "99.00",
                "expiry" => "2026-02-06",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-07",
                "business_name" => "SZ SEWING",
                "subscription" => "Monthly",
                "payment_id" => "745-757",
                "amount" => "99.00",
                "expiry" => "2026-02-06",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-08",
                "business_name" => "Ballito House Keeping",
                "subscription" => "Monthly",
                "payment_id" => "747-759",
                "amount" => "99.00",
                "expiry" => "2026-02-07",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-08",
                "business_name" => "Impact Fencing",
                "subscription" => "Annual",
                "payment_id" => "750-762",
                "amount" => "990.00",
                "expiry" => "2027-01-07",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-09",
                "business_name" => "Spark Star",
                "subscription" => "Monthly",
                "payment_id" => "259-271",
                "amount" => "99.00",
                "expiry" => "2026-02-08",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-09",
                "business_name" => "Remax Property",
                "subscription" => "Monthly",
                "payment_id" => "752-764",
                "amount" => "99.00",
                "expiry" => "2026-02-08",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-11",
                "business_name" => "Jawet Construction",
                "subscription" => "Monthly",
                "payment_id" => "392-404",
                "amount" => "99.00",
                "expiry" => "2026-02-10",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-14",
                "business_name" => "The Cleaning Lady",
                "subscription" => "Monthly",
                "payment_id" => "755-767",
                "amount" => "99.00",
                "expiry" => "2026-02-13",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-19",
                "business_name" => "Garden Fanatics",
                "subscription" => "Annual",
                "payment_id" => "759-771",
                "amount" => "990.00",
                "expiry" => "2027-01-18",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-22",
                "business_name" => "Eco-Rubber",
                "subscription" => "Monthly",
                "payment_id" => "763-775",
                "amount" => "99.00",
                "expiry" => "2026-02-21",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-01-23",
                "business_name" => "Holistic",
                "subscription" => "Monthly",
                "payment_id" => "765-777",
                "amount" => "99.00",
                "expiry" => "2026-02-22",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-02-02",
                "business_name" => "Seelan",
                "subscription" => "Monthly",
                "payment_id" => "771-783",
                "amount" => "99.00",
                "expiry" => "2026-03-01",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-02-04",
                "business_name" => "Echelon Virtual",
                "subscription" => "Monthly",
                "payment_id" => "EFT",
                "amount" => "99.00",
                "expiry" => "2026-03-03",
                "annual" => 0,
                "monthly" => 1,
                "expired" => 0,
            ],
            [
                "date" => "2026-02-07",
                "business_name" => "Dolcobiz",
                "subscription" => "Annual",
                "payment_id" => "785-797",
                "amount" => "990.00",
                "expiry" => "2027-02-06",
                "annual" => 1,
                "monthly" => 0,
                "expired" => 0,
            ]
        ];

        $subscriptionModel = new SubscriptionsModel($this->db);

        $payload = [];
        foreach($subscriptions as $item) {
            $where = " and 
                listings.business_name like '{$item['business_name']}%'
            ";
            $this->db->prepareStatement($this->selectStatement($where, "", ""));
            $this->db->execute();
            $business = $this->db->fetch();

            $subscription = $subscriptionModel->find(['unique_key_id' => $listing->unique_key_id, 'business_id' => $listing->id]);
            $payload[] = [
                'id' => $subscription->id,
                'billing_cycle' => $item['subscription'],
                'next_billing' => $item['expiry'],
                'status' => "Paid",
                "paid" => 1
            ];
        } 

        $rowCount = $subscriptionModel->updateBulk($payload);
        return true;
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
                subscriptions.paid DESC,
                CASE 
                    WHEN subscriptions.paid = 1 THEN subscriptions.payment_date
                    ELSE NULL 
                END DESC,
                
                CASE 
                    WHEN subscriptions.paid = 0 THEN listings.free 
                    ELSE NULL 
                END DESC,
                listings.sort_index DESC;
            $limit
        ";
        return $s_query;
    }

    public function selectReferralsStatement($where = "", $limit = "", $order_by = "") {
        $s_query = "
            select 
                listings.id,
                listings.unique_key_id,
                listings.user_key_id,
                listings.pre_reg_id,
                listings.sort_index,
                listings.business_name,
                listings.vat_number,
                listings.registration_number,
                listings.country_code,
                listings.contact_number,
                listings.office_number,
                listings.email,
                listings.person_name,
                listings.person_surname,
                listings.description,
                listings.logo,
                listings.whatsapp,
                listings.google_url,
                listings.website_url,
                listings.facebook_url,
                listings.x_url,
                listings.instagram_url,
                listings.comments,
                listings.stars_avg,
                listings.referral,
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

                referrals.id as referral_id,
                referrals.last_called,
                referrals.closed,
                referrals.closed_by,
                referrals.closed_date

            from businesses
            inner join subscriptions on subscriptions.business_id = listings.id
            inner join referrals on referrals.business_id = listings.id
            where listings.deleted = '' $where
            $order_by 
            $limit
        ";
        return $s_query;
    }

}