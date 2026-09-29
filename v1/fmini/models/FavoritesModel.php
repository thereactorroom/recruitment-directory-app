<?php 

class FavoritesModel extends Model {
    
    public function __construct($db = null) {
        parent::__construct("favorites", $db);
    }

}