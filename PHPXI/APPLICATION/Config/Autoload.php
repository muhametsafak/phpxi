<?php
/**
 * Choose "true" if you are going to use a database connection
 * Thus, the database connection is made automatically and the classes and methods you can use are kept ready.
 * $config["db"] = false;
 */
$config["db"] = false;

/**
 * Specify the database groups to be linked automatically.
 * If a single group is specified, it is used as "$this->db". If it is specified more than once, it is used as "$this->db->$ db_group_name".
 * See to change database connection settings; "/phpxi_application/config/database.php"
 * $config["connect_db"] = array("default");
 */
$config["connect_db"] = array("default");


/**
 * Upload the Upload library offered by PHPXI?
 * If you are not going to install, change it to "false".
 * $config["upload"] = true;
 */
$config["upload"] = true;

/**
 * Specify helpers to be automatically loaded at system startup.
 * 
 * urls_helper is provided by PHPXI and includes useful functions like "base_url".
 * You can create your own helper in the "/phpxi_application/helper/" directory.
 * Note that the filename must end in "_helper". Sample file name; It should be similar to "myHelper_helper.php".
 * 
 * $config["helper"] = array();
 */
$config["helper"] = array("welcome");

/**
 * Specify config to be automatically loaded at system startup.
 * 
 * $config["config"] = array();
 */
$config["config"] = array();

/**
 * $config["model"] = array("FileClassName" => "fileclass");
 */
$config["model"] = array();

/**
 * $config["libraries"] = array("filename");
 */
$config["libraries"] = array();
