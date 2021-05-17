<?php
namespace Application\Controller;

class Error extends \PHPXI\Controller
{

    public function error_404()
    {
        $this->http->response(404);
        $this->load->view("error/404.php");
    }

}
