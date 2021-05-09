<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Debugging;

class Logger
{
    private static function is_log_file($path)
    {
        if (!file_exists($path)) {
            if (!touch($path)) {
                return false;
            }
        }
        return true;
    }

    private static function interpolate($message, array $context = array())
    {
        $replace = array();
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        return strtr($message, $replace);
    }

    private static function write($path, $log)
    {
        if (self::is_log_file($path)) {
            error_log($log, 3, $path);
        }
    }

    public static function system(string $message, array $context = [])
    {
        $path = WEIGHT_PATH . 'Logs/system_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . self::interpolate($message, $context) . " \n";
        self::write($path, $log);
    }

    public static function emergency(string $message, array $context = [])
    {
        $path = WEIGHT_PATH . 'Logs/emergency_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . self::interpolate($message, $context) . " \n";
        self::write($path, $log);
    }

    public static function alert(string $message, array $context = [])
    {
        $path = WEIGHT_PATH . 'Logs/alert_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . self::interpolate($message, $context) . " \n";
        self::write($path, $log);
    }

    public static function critical(string $message, array $context = [])
    {
        $path = WEIGHT_PATH . 'Logs/critical_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . self::interpolate($message, $context) . " \n";
        self::write($path, $log);
    }

    public static function error(string $message, array $context = [])
    {
        $path = WEIGHT_PATH . 'Logs/error_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . self::interpolate($message, $context) . " \n";
        self::write($path, $log);
    }

    public static function warning(string $message, array $context = [])
    {
        $path = WEIGHT_PATH . 'Logs/warning_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . self::interpolate($message, $context) . " \n";
        self::write($path, $log);
    }

    public static function notice(string $message, array $context = [])
    {
        $path = WEIGHT_PATH . 'Logs/notice_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . self::interpolate($message, $context) . " \n";
        self::write($path, $log);
    }

    public static function debug(string $message, array $context = [])
    {
        $path = WEIGHT_PATH . 'Logs/debug_' . date("Ymd") . '.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . self::interpolate($message, $context) . " \n";
        self::write($path, $log);
    }

    public static function log(string $level, string $message, array $context = [])
    {
        switch (strtolower($level)) {
            case 'system':
                self::system($message, $context);
                break;
            case 'emergency':
                self::emergency($message, $context);
                break;
            case 'alert':
                self::alert($message, $context);
                break;
            case 'critical':
                self::critical($message, $context);
                break;
            case 'error':
                self::error($message, $context);
                break;
            case 'warning':
                self::warning($message, $context);
                break;
            case 'notice':
                self::notice($message, $context);
                break;
            case 'debug':
                self::debug($message, $context);
                break;
        }
    }

}
