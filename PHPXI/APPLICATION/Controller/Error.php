<?php
namespace Application\Controller;

use \PHPXI\Controller;

class Error extends Controller
{

    public function error_404()
    {
        die("PHPXI - Error 404 : File Not Found");
    }

}
