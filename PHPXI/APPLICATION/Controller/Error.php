<?php
namespace Application\Controller;

if(!defined("INDEX")){ die("You are not authorized to access"); }

class Error extends \PHPXI\Controller
{

    public function error_404()
    {
        $this->http->response(404);
        $this->load->view("error/404.php");
    }

}
