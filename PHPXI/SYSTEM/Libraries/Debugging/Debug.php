<?php
/**
 * Debug.php
 *
 * This file is part of PHPXI.
 *
 * @package    Debug.php @ 2021-05-11T20:47:38.266Z
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2021 PHPXI Open Source MVC Framework
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt  GNU GPL 3.0
 * @version    1.6
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
        $error = error_get_last();
        if (is_array($error)) {
            return call_user_func_array([$this, "errorHandler"], $error);
        }

        return true;
    }

    /**
     * @param $type
     * @param $message
     * @param $file
     * @param $line
     */
    public function errorHandler($type, $message, $file, $line)
    {
        ob_clean();
        if (!\Config\Development::PHP_DEFAULT_ERROR_REPORTING_STATUS) {
            @header('HTTP/1.1 500 Internal Server Error', true, 500);
            if (\Config\Development::DEVELOPMENT) {
                $handler = new \PHPXI\Libraries\Debugging\ErrorHandler($type, $file, $line, $message);
                $handler->action();
            } else {
                require SYSTEM_PATH . 'Libraries/Debugging/Whoops.php';
            }
            exit;
        }
    }

}
