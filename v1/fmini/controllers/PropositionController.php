<?php 

class PropositionController extends Controller {
    
    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }

    public function get() {
        if ($this->method != "get"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $propositionModel = new PropositionModel($this->db);
            $proposition = $propositionModel->find(["unique_key_id" => $this->input->unique_key_id]);
            return $this->utils::response(true, "", $proposition);
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function save() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }

            $propositionModel = new PropositionModel($this->db);
            $proposition = $propositionModel->find(["id" => $this->input->id]);

            $content = $this->input->content;
            if (!empty($this->input->images)){
                $baseurl = ENV_HOST . "/modules/module_dev/images/recruitment_directory/";
                $basepath = ENV_PATH . "images/recruitment_directory/";
                $content = $this->fimages->moveTempImages($content, $this->input->images, $this->input->s_width, ENV_HOST, $baseurl, $basepath);
            }

            if ($proposition) {
                $propositionModel->update([
                    "id" => $proposition->id,
                    "title" => $this->input->title,
                    "content" => $content
                ]);
            } else {
                $propositionModel->insert([
                    "unique_key_id" => $this->input->unique_key_id,
                    "title" => $this->input->title,
                    "content" => $content
                ]);
            }
            return $this->utils::response(true, "", "Operation Success");
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
            $propositionModel = new PropositionModel($this->db);
            $propositionModel->delete($this->input->id);
            return $this->utils::response(true, "", "Operation Success");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}

