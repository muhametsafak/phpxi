<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */

/**
 * Specify helpers to be automatically loaded at system startup.
 * 
 * You can create your own helper in the "/PHPXI/APPLICATION/Helpers/" directory.
 * Note that the filename must end in "_helper". Sample file name; It should be similar to "myHelper_helper.php".
 * 
 * $config["helper"] = [];
 */
$config["helper"] = [];

/**
 * Specify config to be automatically loaded at system startup.
 * 
 * $config["config"] = [];
 */
$config["config"] = [];

/**
 * If you want to access the model you created in "use ModelName" type,
 * you must define it in $config['model'].
 */
$config["model"] = [
    "MyOneModel" => "\\Application\\Model\\MyOneModel",
    "MyTwoModel" => "\\Application\\Model\\MyTwoModel"
];
