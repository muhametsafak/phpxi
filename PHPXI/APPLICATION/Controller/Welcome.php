<?php
namespace Controller;

class Welcome extends XI_Controller{

    public function index(){
        $this->benchmark->start("index");
        $data = array();
        $data["title"] = "Welcome to PHPXI";
        $this->view("welcome", $data);
    }
    
    public function not_found($param = ""){
        /**
         * Error 404 - Page Not Found
         */
        if($param == ""){
            $param = "Error 404 - File Not Found";
        }
        $this->http->response(404);
        echo $param;
    }

    public function demo(){
       echo "Selam burası demo"; 
    }
}
