<?php
/**
 * Upload.php
 *
 * This file is part of PHPXI.
 *
 * @package    Upload.php @ 2021-05-12T20:27:24.040Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6.2
 * @link       http://phpxi.net
 *
 * PHPXI is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PHPXI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PHPXI.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Config;

if(!defined("INDEX")){ die("You are not authorized to access"); }

class Upload
{

    /**
     * Must be specified in Byte.
     * Example : 1MB => 1 x (1024 x 1024) = 1048576
     */
    const SIZE_LIMIT = 3145728; //3MB

    /**
     * Directory path to upload
     * const PATH = PUBLIC_PATH . "uploads/";
     */
    const PATH = PUBLIC_PATH . "uploads/";

    /**
     * Upload directory url address
     * const DIR_URL = BASE_URL . "/uploads/";
     */
    const DIR_URL = BASE_URL . "/uploads/";

    /**
     * Specify the types of files to be allowed to upload
     * const FILE_TYPE = ["image/png", "image/jpeg", "image/jpg", "image/gif"];
     */
    const FILE_TYPE = ["image/png", "image/jpeg", "image/jpg", "image/gif"];

    /**
     * Specify the file extensions that will be allowed to be uploaded.
     * const FILE_EXTENSION = ["jpg", "jpeg", "png", "gif"];
     */
    const FILE_EXTENSION = ["jpg", "jpeg", "png", "gif"];

    /**
     * Choose the amount of compression of "jpg" and "jpeg" type files
     * It can take a value between 0 and 100.
     * The smaller the value, the smaller the file size.
     * Note that this process also affects the quality of the image.
     * Default value : 75
     * const JPG_COMPRESS = 75;
     */

    const JPG_COMPRESS = 75;

    /**
     * Choose the amount of compression of "png" type files
     * It can take a value between 0 and 9.
     * The larger the value, the smaller the file size.
     * Note that this process also affects the quality of the image.
     * Default value : 3
     * const PNG_COMPRESS = 3;
     */
    const PNG_COMPRESS = 3;

}
