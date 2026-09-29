<?php 

class CommentsController extends Controller {
    
    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }

    public function list() {
        if ($this->method != "get"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new CommentsService();
            $uniqueKeyService = new UniqueKeyService();

            $unique_key = $uniqueKeyService->getById($this->input->unique_key_id);
            $comments = $service->getComments($this->input);

            $results = [];
            foreach ($comments as $comment){
                $member = $this->utils::getMember($comment->user_id, $unique_key->community_id); 
                $comment->name = $member->name;
                $comment->surname = $member->surname;
                $comment->mobile = $member->mobile;
                $comment->picture = !empty($member->picture) ? $this->utils::$member_pic_url . $member->picture : "";
                $results[] = $comment;
            }
            return $this->utils::response(true, "", $results);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function list_recommended() {
        if ($this->method != "get"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new CommentsService();
            $uniqueKeyService = new UniqueKeyService();

            $unique_key = $uniqueKeyService->getById($this->input->unique_key_id);
            $comments = $service->getComments($this->input);

            $results = [];
            foreach ($comments as $comment){
                $member = $this->utils::getMember($comment->user_id, $unique_key->community_id); 
                $comment->name = $member->name;
                $comment->surname = $member->surname;
                $comment->mobile = $member->mobile;
                $comment->picture = !empty($member->picture) ? $this->utils::$member_pic_url . $member->picture : "";
                $results[] = $comment;
            }
            return $this->utils::response(true, "", $results);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function insert() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $listingsService = new ListingsService();
            $commentsService = new CommentsService();

            $response = $commentsService->insertComment($this->input);
            if ($response) {
                $listingsService->calculateCommentsAverage($this->input);
            }
            return $this->utils::response(true, "Comment added successfully");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function update() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $listingsService = new ListingsService();
            $commentsService = new CommentsService();

            $response = $commentsService->updateComment($this->input);
            if ($response) {
                $listingsService->calculateCommentsAverage($this->input);
            }
            return $this->utils::response(true, "Comment added successfully");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function delete() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $listingsService = new ListingsService();
            $commentsService = new CommentsService();

            $response = $commentsService->deleteComment($this->input);
            if ($response) {
                $listingsService->calculateCommentsAverage($this->input);
            }
            return $this->utils::response(true, "Comment added successfully");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }
}