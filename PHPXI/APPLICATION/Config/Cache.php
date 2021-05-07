<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */

/**
 * HTML Cache Settings
 */
$config['HTML'] = [
    /**
     * Set it to true to turn on HTML caching.
     */
    "status"    => false,
    /**
     * Specifies the validity period of HTML caches in seconds.
     * 1 hour is 3600 seconds.
     * 1 day is 86400 seconds.
     * It has no effect if HTML caching is not enabled.
     */
    "timeout"   => 86400,
    /**
     * Specifies the full directory path to store HTML caching.
     */
    "path"      => WEIGHT_PATH . 'HTML/'
];
