<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Validation;

use \PHPXI\Libraries\Input\Base as Base;

class Token
{

    public static function get()
    {
        return Base::get("_token", "validation");
    }

    public static function verify()
    {
        if(Base::get("_token", "validation")){
            if (Input::post("_token") == Base::get("_token", "validation")) {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }

    }

}
