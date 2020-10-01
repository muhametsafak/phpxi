<?php
namespace Controller;

class Welcome extends XI_Controller{

    public function index(){
        $this->benchmark->start("index");
        $data = array();
        $data["title"] = "Welcome to PHPXI";
        $this->view("welcome", $data);
        $this->benchmark->stop("index");
    }
    
    public function not_found($param = ""){
        /**
         * Error 404 - Page Not Found
         */
        echo $param;
    }
}
