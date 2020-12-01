<?php

if(!function_exists("phpxi_autoload")){
    function phpxi_autoload(string $class) {

        // project-specific namespace prefix
        $prefix = 'PHPXI\\';
        // base directory for the namespace prefix
        $base_dir = SYSTEM . 'Includes/';
    
        // does the class use the namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        // get the relative class name
        $relative_class = substr($class, $len);
        
        

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // if the file exists, require it
        if (file_exists($file)) {
            require $file;
        }
    }
}

spl_autoload_register("phpxi_autoload");
