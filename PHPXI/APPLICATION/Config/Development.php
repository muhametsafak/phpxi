<?php
/**
 * Development.php
 *
 * This file is part of PHPXI.
 *
 * @package    Development.php @ 2021-05-12T12:30:19.945Z
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

class Development
{
    /**
     * Turns developer mode on / off.
     * Change it to false before publishing for security purposes
     */
    const DEVELOPMENT = true;

    /**
     * Defines how the developer tool should react to errors caught.
     * String or NULL
     * Declare the action of type string. Or NULL will make the debug tool unresponsive.
     *
     * String : "view_report" => It shows the error report publicly. We do not recommend using this online.
     * String : "log" => Prints the error to the server's error_log file.
     * String : "dev@localhost.net" => It will try to send the error notification to the specified e-mail address.
     * String : "192.168.1.1" => It makes error reports available only over the specified IP address.
     * NULL : null => Error reporting is overridden.
     * 
     * const ACTION = "view_report";
     * const ACTION = "log";
     * const ACTION = "dev@localhost.net";
     * const ACTION = "127.0.0.1";
     * const ACTION = null;
     */
    const ACTION = "view_report";

    /**
     * Turns PHP's default error reporting system on / off.
     * If it is opened, PHPXI development tools will be disabled.
     * const PHP_DEFAULT_ERROR_REPORTING_STATUS = false;
     */
    const PHP_DEFAULT_ERROR_REPORTING_STATUS = false;

    /**
     * Turns on / off error types to display
     * if PHP's default debug system is turned on.
     */
    const PHP_DEFAULT_ERROR_REPORTING_ACTION = [
        E_ERROR => true,
        E_WARNING => true,
        E_PARSE => true,
        E_NOTICE => true,
        E_CORE_ERROR => true,
        E_CORE_WARNING => true,
        E_COMPILE_ERROR => true,
        E_COMPILE_WARNING => true,
        E_USER_ERROR => true,
        E_USER_WARNING => true,
        E_USER_NOTICE => true,
        E_STRICT => true,
        E_RECOVERABLE_ERROR => true,
        E_DEPRECATED => true,
        E_USER_DEPRECATED => true,
        E_ALL => false
    ];

}
