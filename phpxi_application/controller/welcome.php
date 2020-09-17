<?php
if(!defined("PHPXI")){
    die("You cannot access this page.");
}

class welcome extends PHPXI_CONTROLLER{

    public function index(){
        $data = array();
        $data["title"] = "Welcome to PHPXI";

        $this->view("welcome", $data);
    }
    
}
