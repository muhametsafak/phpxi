<?php
namespace PHPXI\Libraries\Http;

use \PHPXI\Libraries\Base\Base as Base;

class Responsive
{

    public function __set($property, $value)
    {
        Base::set($property, $value, "responsive");
    }

    public function __get($property)
    {
        return Base::get($property, "respnsive");
    }

}
