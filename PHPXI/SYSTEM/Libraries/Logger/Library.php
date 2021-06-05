<?php
/**
 * Logger/Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Logger/Library.php @ 2021-05-11T20:50:56.295Z
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


namespace PHPXI\Libraries\Logger;

if(!defined("INDEX")){ die("You are not authorized to access"); }

class Library
{
    /**
     * @param $path
     */
    private function is_log_file($path): bool
    {
        if (!file_exists($path)) {
            if (!touch($path)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $message
     * @param array $context
     */
    private function interpolate(string $message, array $context = []): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }

    /**
     * @param string $path
     * @param string $log
     */
    private function write(string $path, string $log): void
    {
        if ($this->is_log_file($path)) {
            error_log($log, 3, $path);
        }
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function system(string $message, array $context = []): void
    {
        $path = WEIGHT_PATH . 'Logs/system_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . $this->interpolate($message, $context) . " \n";
        $this->write($path, $log);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function emergency(string $message, array $context = []): void
    {
        $path = WEIGHT_PATH . 'Logs/emergency_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . $this->interpolate($message, $context) . " \n";
        $this->write($path, $log);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function alert(string $message, array $context = []): void
    {
        $path = WEIGHT_PATH . 'Logs/alert_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . $this->interpolate($message, $context) . " \n";
        $this->write($path, $log);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function critical(string $message, array $context = []): void
    {
        $path = WEIGHT_PATH . 'Logs/critical_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . $this->interpolate($message, $context) . " \n";
        $this->write($path, $log);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function error(string $message, array $context = []): void
    {
        $path = WEIGHT_PATH . 'Logs/error_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . $this->interpolate($message, $context) . " \n";
        $this->write($path, $log);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function warning(string $message, array $context = []): void
    {
        $path = WEIGHT_PATH . 'Logs/warning_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . $this->interpolate($message, $context) . " \n";
        $this->write($path, $log);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function notice(string $message, array $context = []): void
    {
        $path = WEIGHT_PATH . 'Logs/notice_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . $this->interpolate($message, $context) . " \n";
        $this->write($path, $log);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function debug(string $message, array $context = []): void
    {
        $path = WEIGHT_PATH . 'Logs/debug_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . $this->interpolate($message, $context) . " \n";
        $this->write($path, $log);
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public function log(string $level, string $message, array $context = []): void
    {
        switch (strtolower($level)) {
            case 'system':
                $this->system($message, $context);
                break;
            case 'emergency':
                $this->emergency($message, $context);
                break;
            case 'alert':
                $this->alert($message, $context);
                break;
            case 'critical':
                $this->critical($message, $context);
                break;
            case 'error':
                $this->error($message, $context);
                break;
            case 'warning':
                $this->warning($message, $context);
                break;
            case 'notice':
                $this->notice($message, $context);
                break;
            case 'debug':
                $this->debug($message, $context);
                break;
        }
    }

}
