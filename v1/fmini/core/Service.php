<?php 

class Service {
    
    /** @var object $utils - provide interface utilities */
    protected $utils;

    /** @var object $curl - handles curl libraries */
    protected $curl;

    /** @var object $model - handles the associated table model */
    protected $model;

    /** @var object $db - The database handler class reference */
    protected $db;

    /** @var object $fimages - TheFusion Image class reference */
    protected $fimages;

    /** @method default constructor */
    public function __construct() {
        $this->utils = new Utils();
        $this->curl = new Curl();
        $this->model = null;
        $this->db = new DB();
        $this->fimages = new FusionImages();
    }

}