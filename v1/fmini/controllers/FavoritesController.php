<?php 

class FavoritesController extends Controller {
    
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
            $service = new FavoritesService();
            $traders = $service->getFavorites($this->input);
            return $this->utils::response(true, "", $traders);
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
            $service = new FavoritesService();
            if ($service->favoritesExists($this->input)) {
                return $this->utils::response(false, "Business already added to favorites");
            }

            $service->insertFavorites($this->input);
            return $this->utils::response(true, "Favorite added successfully");
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
            $service = new FavoritesService();
            $service->deleteFavorites($this->input);
            return $this->utils::response(true, "Favorite deleted successfully");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

    public function deleteAll() {
        if ($this->method != "post"){
            return utils::response(false, "Invalid request method");
        }
        try {
            if (!isset($this->input)) {
                return $this->utils::response(false, "Invalid request inputs");
            }
            $service = new FavoritesService();
            $service->deleteAllFavorites($this->input);
            return $this->utils::response(true, "Favorite deleted successfully");
        } catch (Exception $e) {
            return $this->utils::response(false, "Error: " . $e->getMessage());
        }
    }

}




