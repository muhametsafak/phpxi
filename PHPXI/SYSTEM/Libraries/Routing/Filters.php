<?php
/**
 * Author: Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * Project: PHPXI MVC Framework <phpxi.net>
 */
namespace PHPXI\Libraries\Routing;

use PHPXI\Libraries\Base\Base as Base;

class Filters
{
    /**
     * @var array
     */
    private static $filters = [];

    /**
     * @var array
     */
    private static $only_before = [];

    /**
     * @var array
     */
    private static $only_after = [];

    /**
     * @var array
     */
    private static $run_before = [];

    /**
     * @var array
     */
    private static $run_after = [];

    /**
     * @var array
     */
    public static $patterns = [
        '{int[0-9]?}' => '([0-9]+)',
        '{string[0-9]?}' => '([a-zA-Z0-9-_]+)',
        ':id[0-9]?' => '([0-9]+)',
        ':str[0-9]?' => '([a-zA-Z0-9-_]+)',
        ':any' => '(.*)'
    ];

    /**
     * @param $name
     */
    private static function filter_load($name)
    {
        if (!in_array($name, self::$filters)) {
            $filterName = "\\Application\\Filters\\" . ucfirst($name);
            self::$filters[$name] = new $filterName();
        }
    }

    /**
     * @param string $name
     */
    public static function add(string $name)
    {
        self::add_before($name);
        self::add_after($name);
    }

    /**
     * @param string $name
     */
    public static function add_before(string $name)
    {
        if (!in_array($name, self::$only_before)) {
            self::$only_before[] = $name;
        }
    }

    /**
     * @param string $name
     */
    public static function add_after(string $name)
    {
        if (!in_array($name, self::$only_after)) {
            self::$only_after[] = $name;
        }
    }

    public static function prepare()
    {
        $global = \Config\Filters::GLOBALS;
        $global_filters = $global['filters'];
        if (is_array($global_filters) and sizeof($global_filters) > 0) {
            foreach ($global_filters as $row) {
                self::add($row);
            }
        }

        $global_before = $global['before'];
        if (is_array($global_before) and sizeof($global_before) > 0) {
            foreach ($global_before as $row) {
                self::add_before($row);
            }
        }

        $global_after = $global['after'];
        if (is_array($global_after) and sizeof($global_after) > 0) {
            foreach ($global_after as $row) {
                self::add_after($row);
            }
        }
    }

    /**
     * @param $filters
     */
    public static function route_filter($filters)
    {
        if (is_array($filters) and sizeof($filters) > 0) {
            foreach ($filters as $row) {
                self::add($filters);
            }
        }
        if (is_string($filters) and $filters != "") {
            self::add($filters);
        }
    }

    /**
     * @param $url
     */
    public static function global_route_filter($url)
    {
        $filters = \Config\Filters::FILTERS;
        if (is_array($filters) and sizeof($filters) > 0) {
            foreach ($filters as $filterName => $apply) {

                if (isset($apply['before']) and is_array($apply['before']) and sizeof($apply['before']) > 0) {
                    foreach ($apply['before'] as $path) {
                        foreach (self::$patterns as $key => $value) {
                            $path = preg_replace('#' . $key . '#', $value, $path);
                        }
                        $pattern = '#^' . $path . '$#';
                        if (preg_match($pattern, $url, $params)) {
                            self::add_before($filterName);
                        }
                    }
                }

                if (isset($apply['after']) and is_array($apply['after']) and sizeof($apply['after']) > 0) {
                    foreach ($apply['after'] as $path) {
                        foreach (self::$patterns as $key => $value) {
                            $path = preg_replace('#' . $key . '#', $value, $path);
                        }
                        $pattern = '#^' . $path . '$#';
                        if (preg_match($pattern, $url, $params)) {
                            self::add_after($filterName);
                        }
                    }
                }

            }
        }
    }

    public static function before()
    {
        if (is_array(self::$only_before) and sizeof(self::$only_before) > 0) {
            foreach (self::$only_before as $row) {
                self::filter_load($row);
                if (!in_array($row, self::$run_before)) {
                    if (self::$filters[$row]->before(Base::$models['request']) === false) {
                        return false;
                    }
                    self::$run_before[] = $row;
                }
            }
        }

        return true;
    }

    public static function after()
    {
        if (is_array(self::$only_after) and sizeof(self::$only_after) > 0) {
            foreach (self::$only_after as $row) {
                self::filter_load($row);
                if (!in_array($row, self::$run_after)) {
                    if (self::$filters[$row]->after(Base::$models['request'], Base::$models['response']) === false) {
                        return false;
                    }
                    self::$run_after[] = $row;
                }
            }
        }

        return true;
    }

}
