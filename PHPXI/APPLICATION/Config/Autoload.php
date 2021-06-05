<?php
/**
 * Autoload.php
 *
 * This file is part of PHPXI.
 *
 * @package    Autoload.php @ 2021-05-11T23:36:08.787Z
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

class Autoload
{

    const PREFIX = [
        //"Libraries" => APPLICATION_PATH . "/Libraries/",
    ];


    const DB = [
        //"db" => \Config\Database::DB //$this->db
    ];

    /**
     * Specify helpers to be automatically loaded at system startup.
     *
     * You can create your own helper in the "/PHPXI/APPLICATION/Helpers/" directory.
     * Note that the filename must end in "_helper". Sample file name; It should be similar to "My_helper.php".
     *
     * const HELPER = [];
     */
    const HELPER = [
        "My"
    ];

    /**
     * Specify config to be automatically loaded at system startup.
     *
     * const CONFIG = [];
     */
    const CONFIG = [];

    /**
     * If you want to access the model you created in "use ModelName" or "$this->modelname" type,
     * you must define it in
     * const MODEL = [];
     */
    const MODEL = [
        "mymodel" => "\Application\Model\MyModel"
    ];

    /**
     * If you want to access the model you created in "use ModelName" or "$this->modelname" type,
     * you must define it in
     * const LIBRARIES = [];
     */
    const LIBRARIES = [
        "benchmark" => "\PHPXI\Libraries\Benchmark\Benchmark",
        "cookie" => "\PHPXI\Libraries\Cookie\Cookie",
        "file" => "\PHPXI\Libraries\File\File",
        "form" => "\PHPXI\Libraries\Form\Form",
        "hash" => "\PHPXI\Libraries\Hash\Hash",
        "hook" => "\PHPXI\Libraries\Hooking\Hook",
        "http" => "\PHPXI\Libraries\Http\Http",
        "input" => "\PHPXI\Libraries\Input\Input",
        "logger" => "\PHPXI\Libraries\Logger\Logger",
        "server" => "\PHPXI\Libraries\Server\Server",
        "session" => "\PHPXI\Libraries\Session\Session",
        "upload" => "\PHPXI\Libraries\Upload\Upload",
        "validation" => "\PHPXI\Libraries\Validation\Validation",
        "token" => "\PHPXI\Libraries\Validation\Token",
        "lang" => "\PHPXI\Libraries\Language\Language",
    ];

}
