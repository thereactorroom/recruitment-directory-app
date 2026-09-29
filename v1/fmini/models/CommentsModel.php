<?php 

class CommentsModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("comments", $db);
    }

}