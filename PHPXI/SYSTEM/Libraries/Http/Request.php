<?php
namespace PHPXI\Libraries\Http;

use \PHPXI\Libraries\Base\Base as Base;

class Request
{

    public function __set($property, $value)
    {
        Base::set($property, $value, "request");
    }

    public function __get($property)
    {
        return Base::get($property, "request");
    }

}
