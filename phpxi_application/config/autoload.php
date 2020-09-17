<?php
if(!defined("PHPXI")){
    die("You cannot access this page.");
}
$config["autoload"] = array();

/**
 * Choose "true" if you are going to use a database connection
 * Thus, the database connection is made automatically and the classes and methods you can use are kept ready.
 * $config["autoload"]["db"] = true;
 */
$config["autoload"]["db"] = true;

/**
 * Specify the database groups to be linked automatically.
 * If a single group is specified, it is used as "$this->db". If it is specified more than once, it is used as "$this->db->$ db_group_name".
 * See to change database connection settings; "/phpxi_application/config/database.php"
 * $config["autoload"]["connect_db"] = array("default");
 */
$config["autoload"]["connect_db"] = array("default");

/**
 * Choose whether the open source PHPMailler library should be automatically loaded or not.
 * If "true" is selected; Can be used as "$this->mailler".
 * If you are not going to use this class; Change it to "false".
 * $config["autoload"]["phpmailer"] = true;
 */
$config["autoload"]["phpmailer"] = true;

/**
 * Install the Input library?
 * $config["autoload"]["input"] = true;
 */
$config["autoload"]["input"] = true;

/**
 * Upload the Upload library offered by PHPXI?
 * If you are not going to install, change it to "false".
 * $config["autoload"]["upload"] = true;
 */
$config["autoload"]["upload"] = true;

/**
 * Specify helpers to be automatically loaded at system startup.
 * 
 * urls_helper is provided by PHPXI and includes useful functions like "base_url".
 * You can create your own helper in the "/phpxi_application/helper/" directory.
 * Note that the filename must end in "_helper". Sample file name; It should be similar to "myHelper_helper.php".
 * 
 * $config["autoload"]["helper"] = array("urls");
 */
$config["autoload"]["helper"] = array("urls", "welcome");

