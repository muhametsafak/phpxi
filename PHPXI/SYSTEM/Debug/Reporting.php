<?php
function PHPXI_ShutdownHandler(){
    if(@is_array($error = @error_get_last())){
        return(@call_user_func_array('PHPXI_ErrorHandler', $error));
    };
    return(TRUE);
};

register_shutdown_function('PHPXI_ShutdownHandler');

function PHPXI_ErrorHandler($type, $message, $file, $line){
    switch(MODE){
        case "development" :
            $debug = new PHPXI\SYSTEM\Debug($type, $file, $line, $message);
            return die($debug->development());
        break;
        default : return false;
    }
}

$old_error_handler = set_error_handler("PHPXI_ErrorHandler");
