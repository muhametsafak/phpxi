<?php
/**
 * ErrorHandler.php
 *
 * This file is part of PHPXI.
 *
 * @package    ErrorHandler.php @ 2021-05-11T20:48:20.379Z
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


namespace PHPXI\Libraries\Debugging;

if(!defined("INDEX")){ die("You are not authorized to access"); }

class ErrorHandler
{

    /**
     * @var mixed
     */
    private $type;
    /**
     * @var mixed
     */
    private $file;
    /**
     * @var mixed
     */
    private $line;
    /**
     * @var mixed
     */
    private $message;
    /**
     * @var mixed
     */
    private $name;

    /**
     * @var mixed
     */
    private $action = null;

    /**
     * @param $type
     * @param $file
     * @param $line
     * @param $message
     */
    public function __construct($type, $file, $line, $message)
    {
        $this->type = $type;
        $this->file = $file;
        $this->line = $line;
        $this->message = $message;
        $this->action = \Config\Development::ACTION;
        $this->error_name();
    }

    private function error_name()
    {
        $_ERRORS = array(
            0x0001 => 'E_ERROR',
            0x0002 => 'E_WARNING',
            0x0004 => 'E_PARSE',
            0x0008 => 'E_NOTICE',
            0x0010 => 'E_CORE_ERROR',
            0x0020 => 'E_CORE_WARNING',
            0x0040 => 'E_COMPILE_ERROR',
            0x0080 => 'E_COMPILE_WARNING',
            0x0100 => 'E_USER_ERROR',
            0x0200 => 'E_USER_WARNING',
            0x0400 => 'E_USER_NOTICE',
            0x0800 => 'E_STRICT',
            0x1000 => 'E_RECOVERABLE_ERROR',
            0x2000 => 'E_DEPRECATED',
            0x4000 => 'E_USER_DEPRECATED'
        );
        if (!@is_string($name = @array_search($this->type, @array_flip($_ERRORS)))) {
            $name = 'E_UNKNOWN';
        }
        $this->name = $name;
    }

    private function view_report()
    {
        $line = $this->line;
        $file = $this->file;
        $name = $this->name;
        $message = $this->message;
        $response_status = $this->response_status(http_response_code());
        require SYSTEM_PATH . "Libraries/Debugging/Report.php";
    }

    /**
     * @param int $code
     */
    private function response_status(int $code): string
    {
        switch ($code) {
            case 100:$text = 'Continue';
                break;
            case 101:
                $text = 'Switching Protocols';
                break;
            case 200:
                $text = 'OK';
                break;
            case 201:
                $text = 'Created';
                break;
            case 202:
                $text = 'Accepted';
                break;
            case 203:
                $text = 'Non-Authoritative Information';
                break;
            case 204:
                $text = 'No Content';
                break;
            case 205:
                $text = 'Reset Content';
                break;
            case 206:
                $text = 'Partial Content';
                break;
            case 300:
                $text = 'Multiple Choices';
                break;
            case 301:
                $text = 'Moved Permanently';
                break;
            case 302:
                $text = 'Moved Temporarily';
                break;
            case 303:
                $text = 'See Other';
                break;
            case 304:
                $text = 'Not Modified';
                break;
            case 305:
                $text = 'Use Proxy';
                break;
            case 400:
                $text = 'Bad Request';
                break;
            case 401:
                $text = 'Unauthorized';
                break;
            case 402:
                $text = 'Payment Required';
                break;
            case 403:
                $text = 'Forbidden';
                break;
            case 404:
                $text = 'Not Found';
                break;
            case 405:
                $text = 'Method Not Allowed';
                break;
            case 406:
                $text = 'Not Acceptable';
                break;
            case 407:
                $text = 'Proxy Authentication Required';
                break;
            case 408:
                $text = 'Request Time-out';
                break;
            case 409:
                $text = 'Conflict';
                break;
            case 410:
                $text = 'Gone';
                break;
            case 411:
                $text = 'Length Required';
                break;
            case 412:
                $text = 'Precondition Failed';
                break;
            case 413:
                $text = 'Request Entity Too Large';
                break;
            case 414:
                $text = 'Request-URI Too Large';
                break;
            case 415:
                $text = 'Unsupported Media Type';
                break;
            case 500:
                $text = 'Internal Server Error';
                break;
            case 501:
                $text = 'Not Implemented';
                break;
            case 502:
                $text = 'Bad Gateway';
                break;
            case 503:
                $text = 'Service Unavailable';
                break;
            case 504:
                $text = 'Gateway Time-out';
                break;
            case 505:
                $text = 'HTTP Version not supported';
                break;
            default:
                $text = 'Unknown http status code ';
                break;
        }

        return htmlentities($code) . ' ' . $text;
    }

    private function whoops()
    {
        require SYSTEM_PATH . "Libraries/Debugging/Whoops.php";
    }

    public function action()
    {
        if (!is_null($this->action)) {
            $log = "{" . date(DATE_ATOM) . "} " . $this->name . " " . $this->file . " at line " . $this->line . " \n Message: " . $this->message;
            if ($this->action == "view_report") {
                $this->view_report();
            } elseif ($this->action == "log") {
                error_log($log);
                $this->whoops();
            } elseif (filter_var($this->action, \FILTER_VALIDATE_IP) && $_SERVER["REMOTE_ADDR"] == $this->action) {
                $this->view_report();
            } elseif (filter_var($this->action, \FILTER_VALIDATE_EMAIL)) {
                error_log($log, 1, $this->action);
                $this->whoops();
            } else {
                $this->whoops();
            }
        } else {
            $this->whoops();
        }
    }

}
