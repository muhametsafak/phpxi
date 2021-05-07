<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */

/**
 * Must be specified in Byte.
 * Example : 1MB => 1 x (1024 x 1024) = 1048576
 */
$config["sizeLimit"] = 3145728; //3MB

/**
 * Directory path to upload
 * $config["path"] = PUBLIC_PATH . "uploads/";
 */
$config["path"] = PUBLIC_PATH . "uploads/";

/**
 * Upload directory url address
 * $config['upload']['dir_url'] = BASE_URL."/uploads/";
 */
$config['dir_url'] = BASE_URL."/uploads/";

/**
 * Specify the types of files to be allowed to upload
 * $config["file_type"] = array("image/png", "image/jpeg", "image/jpg", "image/gif");
 */
$config["file_type"] = array("image/png", "image/jpeg", "image/jpg", "image/gif");

/**
 * Specify the file extensions that will be allowed to be uploaded.
 * $config["file_extension"] = array("jpg", "jpeg", "png", "gif");
 */
$config["file_extension"] = array("jpg", "jpeg", "png", "gif");

/**
 * Choose the amount of compression of "jpg" and "jpeg" type files
 * It can take a value between 0 and 100.
 * The smaller the value, the smaller the file size.
 * Note that this process also affects the quality of the image.
 * Default value : 75
 * $config["jpg_compress"] = 75;
 */
$config["jpg_compress"] = 75;

/**
 * Choose the amount of compression of "png" type files
 * It can take a value between 0 and 9.
 * The larger the value, the smaller the file size.
 * Note that this process also affects the quality of the image.
 * Default value : 3
 * $config["png_compress"] = 3;
 */
$config["png_compress"] = 3;
