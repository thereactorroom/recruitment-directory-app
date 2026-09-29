<?php

class ImagesController extends Controller {

    protected $tags = [
        "jpg" => '<img src="data:image/jpeg;base64,', 
        "png" => '<img src="data:image/png;base64,', 
        "webp" => '<img src="data:image/webp;base64,',
        "gif" => '<img src="data:image/gif;base64,'
    ];

    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }

    public function save() {
        try {
            $results = $this->fimages->temp_image($this->input->image);
            if (count($results) > 0) {
                return $this->utils::response(true, "Operation successful", $results);
            } 
            return $this->utils::response(false, "Operation failed");
        } catch(Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}