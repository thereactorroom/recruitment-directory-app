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
            $model = new PropositionModel($this->db);
            // $proposition = $model->find(["unique_key_id" => $this->input->unique_key_id]);
            // $newProposition = str_replace('https://fonq.mobi', '**host**', $proposition->proposition);
            // $model->update([
            //     'id' => $proposition->id,
            //     'proposition' => $newProposition
            // ]);
            $proposition = $model->find(["unique_key_id" => $this->input->unique_key_id]);
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
            $model = new PropositionModel($this->db);
            $proposition = $model->find(["id" => $this->input->id]);

            $content = $this->input->proposition;
            if (!empty($this->input->images)) {
                $baseurl = ENV_HOST . "/modules/module_dev/images/business_listings/";
                $basepath = ENV_PATH . "/images/business_listings/";
                foreach ($this->input->images as $image) {
                    $imagename = uniqid() . ".jpg";
                    $newurl = $baseurl . $imagename;
                    $newpath = $basepath . $imagename;

                    if (copy($image["path"], $newpath)) {
                        unlink($image["path"]);
                        $newurl = ENV_HOST . "/scripts/mthumb/mthumb.php?src={$newurl}&w=360&q=100&zc=6";
                        $content = str_replace($image["url"], $newurl, $content);
                    }
                }
            }

            if ($proposition) {
                $model->update([
                    "id" => $proposition->id,
                    "title" => $this->input->title,
                    "proposition" => $content
                ]);
            } else {
                $model->insert([
                    "unique_key_id" => $this->input->unique_key_id,
                    "title" => $this->input->title,
                    "proposition" => $content
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
            $model = new PropositionModel($this->db);
            $model->delete($this->input->id);
            return $this->utils::response(true, "", "Operation Success");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}

