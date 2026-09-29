<?php 

class FavoritesService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function getFavorites($input) {
        $favoritesModel = new FavoritesModel($this->db);
        $favorites = $favoritesModel->findAll([
            "unique_key_id" => $input->unique_key_id, 
            "user_key_id" => $input->user_key_id,
            "deleted" => ""
        ]);
        return $favorites;
    }

    public function favoritesExists($input) {
        $favoritesModel = new FavoritesModel($this->db);
        $favorite = $favoritesModel->findAll([
            "unique_key_id" => $input->unique_key_id, 
            "user_key_id" => $input->user_key_id,
            "listing_id" => $input->listing_id,
            "deleted" => ""
        ]);
        return $favorite ? true : false;
    }

    public function insertFavorites($input) {
        try {
            $this->db->beginTransaction();
            $favoritesModel = new FavoritesModel($this->db);
            $favoritesModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "listing_id" => $input->listing_id,
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

    public function deleteFavorites($input){
        try {
            $this->db->beginTransaction();
            $favoritesModel = new FavoritesModel($this->db);

            $favorite = $favoritesModel->find([
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "listing_id" => $input->listing_id,
                "deleted" => ""
            ]);

            $favoritesModel->update([
                "id" => $favorite->id,
                "deleted" => $this->utils->getDateTime(),
                "deleted_by" => $input->user_key_id,
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

    public function deleteAllFavorites($input){
        try {
            $this->db->beginTransaction();
            $favoritesModel = new FavoritesModel($this->db);

            $favorites = $favoritesModel->findAll([
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "deleted" => ""
            ]);

            foreach($favorites as $favorite){
                $favoritesModel->update([
                    "id" => $favorite->id,
                    "deleted" => $this->utils->getDateTime(),
                    "deleted_by" => $input->user_key_id,
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function selectStatement($where = "", $limit = "") {
        $s_query = "
            select 
                favorites.id,
                favorites.unique_key_id,
                favorites.user_key_id,
                favorites.listing_id
            from favorites
                inner join listings on listings.id = favorites.listing_id
            where 
                favorites.deleted = '' $where 
            order by favorites.added desc 
            $limit
        ";
        return $s_query;
    }

}




