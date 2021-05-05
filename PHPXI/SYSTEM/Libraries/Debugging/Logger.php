<?php
namespace PHPXI\Libraries\Debugging;

class Logger
{
    private static $before_time = 0;

    private static function timer(){
        $msure = microtime();
        $msure = explode(' ', $msure); 
        $msure = $msure[1] + $msure[0];
        $time_difference = round(($msure - TIMER_START), 5);

        $str = "{" . $time_difference;
        if(self::$before_time != 0){
            $str .= " (" . round(($time_difference - self::$before_time), 6) . ")";
        }
        $str .= "}";
        self::$before_time = $time_difference;
        return $str;
    }

    public static function system(string $log){
        $path = WEIGHT_PATH . 'Logs/system.log';
        $log = "{" . date("d/m/Y H:i:s") . "} " . $log . " ";
        $log .= self::timer();
        $log .= "\n";
        error_log($log, 3, $path);
    }


}
