<?php


class Welcome extends PHPXI_Controller{

    public function index(){
        $this->benchmark->start("index");
        $data = array();
        $data["title"] = "Welcome to PHPXI";
        $this->view("welcome", $data);
        $this->benchmark->stop("index");
    }
    
}
