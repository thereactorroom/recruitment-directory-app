<?php 

class CommentsService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function get($id){
        $where = " and comments.id = :id";
        $this->db->prepareStatement($this->selectStatement($where, ""));
        $this->db->execute(["id" => $id]);
        return $this->db->fetch();
    }

    public function getComments($input) {
        $where = " and 
            comments.unique_key_id = :unique_key_id and 
            comments.listing_id = :listing_id 
        ";
        $limit = "";
        if (isset($input->start_limit)){
            $limit = "limit {$input->start_limit}, {$input->end_limit}";
        }
        $this->db->prepareStatement($this->selectStatement($where, $limit));
        $this->db->execute(["unique_key_id" => $input->unique_key_id, "listing_id" => $input->listing_id]);
        return $this->db->fetchAll();
    }

    public function commentExists($input) {
        $commentsModel = new CommentsModel($this->db);
        $comment = $commentsModel->find([
            "unique_key_id" => $input->unique_key_id,
            "user_key_id" => $input->user_key_id,
            "listing_id" => $input->listing_id
        ]);
        return $comment ? true : false;
    }

    public function insertComment($input) {
        try {
            $this->db->beginTransaction();
            $commentsModel = new CommentsModel($this->db);

            $comment_id = $commentsModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "listing_id" => $input->listing_id,
                "comment" => $input->comment,
                "stars" => $input->stars,
                "updated" => $this->utils::getDateTime()
            ]);

            $this->db->commit();
            return $comment_id;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollback();
            }
            return false;
        }
    }

    public function updateComment($input) {
        try {
            $this->db->beginTransaction();
            $commentsModel = new CommentsModel($this->db);
            $commentsModel->update([
                "id" => $input->id,
                "comment" => $input->comment,
                "stars" => $input->stars,
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

    public function deleteComment($input){
        try {
            $this->db->beginTransaction();
            $commentsModel = new CommentsModel($this->db);
            $deleted = $this->utils->getDateTime();
            $commentsModel->update([
                "id" => $input->id,
                "deleted" => $deleted,
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

    public function selectStatement($where = "", $limit = "") {
        $s_query = "
            select 
                comments.id,
                comments.unique_key_id,
                comments.user_key_id,
                comments.listing_id,
                comments.comment,
                comments.stars,
                comments.added,
                comments.updated,
                comments.deleted,

                listings.name,
                listings.comments,
                listings.stars_avg,
                listings.added as since,

                user_key.user_id
                
            from comments
                inner join listings on listings.id = comments.listing_id
                inner join user_key on user_key.id = comments.user_key_id
            where 
                comments.deleted = '' $where 
            order by comments.updated desc 
            $limit
        ";
        return $s_query;
    }

}



