<?php
namespace Application\Model;

class Model
{

    public function __construct($property)
    {
        
        $this->responsive = new \PHPXI\Libraries\Http\Responsive();

        $this->request = new \PHPXI\Libraries\Http\Request();

        $this->config = new \PHPXI\Libraries\Config\Config();

    }

}
