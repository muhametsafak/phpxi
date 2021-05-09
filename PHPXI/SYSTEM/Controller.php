<?php
namespace Application\Controller;

class Controller
{
    public $responsive;
    public $request;

    public function __construct()
    {

        $this->responsive = new \PHPXI\Libraries\Http\Responsive();

        $this->request = new \PHPXI\Libraries\Http\Request();

        $this->config = new \PHPXI\Libraries\Config\Config();

    }

}
