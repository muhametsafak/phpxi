<?php

class Welcome extends PHPXI_Controller{

    public function index(){
        $data = array();
        $data["title"] = "Welcome to PHPXI";
        $this->view("welcome", $data);
    }
    
}
