<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI;

use \PHPXI\Libraries\Cache\Cache as Cache;
use \PHPXI\Libraries\Config\Config as Config;
use \PHPXI\Libraries\Routing\Route as Route;

class Core
{

    private static $html_output;

    public static function output()
    {
        if (Config::get("cache.HTML.status")) {
            Cache::path(Config::get("cache.HTML.path"));
            Cache::timeout(Config::get("cache.HTML.timeout"));
            Cache::file("%%" . md5(current_url()) . "%%.html");
            if (!Cache::is() || Cache::is_timeout()) {
                self::execute();
                Cache::content(self::$html_output);
                if (!Cache::is()) {
                    Cache::create();
                } else {
                    Cache::write();
                }
                return self::$html_output;
            } else {
                return Cache::read();
            }
        } else {
            return self::execute();
        }
    }

    private static function execute()
    {
        ob_start();
        Route::dispatch();
        self::$html_output = ob_get_clean();
        if (ob_get_length() > 0) {
            ob_end_flush();
        }
        if (Config::get("config.minify")) {
            self::$html_output = minify(self::$html_output);
        }
        return self::$html_output;
    }

}
