<?php

class ImagesController extends Controller {

    public function __construct($input, $method) {
        parent::__construct($input, $method);
    }
    
    public function save() {
        try {
            $results = $this->fimages->saveTempImage($this->input->image);
            if (count($results) > 0) {
                return $this->utils::response(true, "Operation successful", $results);
            } 
            return $this->utils::response(false, "Operation failed");
        } catch(Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}