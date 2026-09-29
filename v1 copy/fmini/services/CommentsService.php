<?php 

class CommentsService extends Service {
    
    public function __construct() {
        parent::__construct();
    }

    public function getComments($input) {
        $where = " and 
            comments.unique_key_id = :unique_key_id and 
            comments.business_id = :business_id 
        ";
        $limit = "";
        if (isset($input->start_limit)){
            $limit = "limit {$input->start_limit}, {$input->end_limit}";
        }
        $this->db->prepareStatement($this->selectStatement($where, $limit));
        $this->db->execute(["unique_key_id" => $input->unique_key_id, "business_id" => $input->business_id]);
        return $this->db->fetchAll();
    }

    public function commentExists($input) {
        $commentsModel = new CommentsModel($this->db);
        $comment = $commentsModel->find([
            "unique_key_id" => $input->unique_key_id,
            "user_key_id" => $input->user_key_id,
            "business_id" => $input->business_id
        ]);
        return $comment ? true : false;
    }

    public function insertComment($input) {
        try {
            $this->db->beginTransaction();
            $commentsModel = new CommentsModel($this->db);

            $commentsModel->insert([
                "unique_key_id" => $input->unique_key_id,
                "user_key_id" => $input->user_key_id,
                "business_id" => $input->business_id,
                "comment" => $input->comment,
                "stars" => $input->stars,
                "updated" => $this->utils::getDateTime()
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
                comments.business_id,
                comments.comment,
                comments.stars,
                comments.added,
                comments.updated,
                comments.deleted,

                businesses.business_name,
                businesses.comments,
                businesses.stars_avg,
                businesses.added as since,

                user_key.user_id
                
            from comments
                inner join businesses on businesses.id = comments.business_id
                inner join user_key on user_key.id = comments.user_key_id
            where 
                comments.deleted = '' $where 
            order by comments.updated desc 
            $limit
        ";
        return $s_query;
    }

}



