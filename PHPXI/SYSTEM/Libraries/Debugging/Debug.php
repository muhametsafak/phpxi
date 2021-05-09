<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Debugging;

class Debug
{

    public function __construct()
    {
        register_shutdown_function([$this, 'shutdonwHandler']);
        set_error_handler([$this, 'errorHandler']);
    }

    public function shutdonwHandler()
    {
        if (@is_array($error = @error_get_last())) {
            return @call_user_func_array([$this, 'errorHandler'], $error);
        }
        return true;
    }

    public function errorHandler($type, $message, $file, $line)
    {
        switch (DEVELOPMENT) {
            case true:
                $handler = new \PHPXI\Libraries\Debugging\ErrorHandler($type, $file, $line, $message);
                return die($handler->printr());
                break;
            default:return false;
        }
    }

}
