<?php
/**
 * Library.php
 *
 * This file is part of PHPXI.
 *
 * @package    Library.php @ 2021-05-13T15:35:30.140Z
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

namespace PHPXI\Libraries\Routing;

use PHPXI\Libraries\Base\Base as Base;
use PHPXI\Libraries\Routing\Filters as Filters;

class Library
{

    /**
     * @var mixed
     */
    public $hasRoute = false;

    /**
     * @var array
     */
    public $current = [
        "controller" => "",
        "cfunction" => "",
        "params" => []
    ];

    /**
     * @var array
     */
    private $uri = [];

    /**
     * @param string $path
     * @param $callback
     * @param array $options
     * @return mixed
     */
    public function get(string $path, $callback, array $options = []): self
    {
        $key = Base::$route['prefix'] . $path;
        Base::$route['get'][$key] = [
            'callback' => $callback,
            'options' => $options
        ];
        Base::$route['last'] = ["get" => $key];

        return $this;
    }

    /**
     * @param string $path
     * @param $callback
     * @param array $options
     * @return mixed
     */
    public function post(string $path, $callback, array $options = []): self
    {
        $key = Base::$route['prefix'] . $path;
        Base::$route['post'][$key] = [
            'callback' => $callback,
            'options' => $options
        ];
        Base::$route['last'] = ["post" => $key];

        return $this;
    }

    /**
     * @param string $path
     * @param $callback
     * @param array $options
     * @return mixed
     */
    public function put(string $path, $callback, array $options = []): self
    {
        $key = Base::$route['prefix'] . $path;
        Base::$route['put'][$key] = [
            'callback' => $callback,
            'options' => $options
        ];
        Base::$route['last'] = ["put" => $key];

        return $this;
    }

    /**
     * @param string $path
     * @param $callback
     * @param array $options
     * @return mixed
     */
    public function delete(string $path, $callback, array $options = []): self
    {
        $key = Base::$route['prefix'] . $path;
        Base::$route['delete'][$key] = [
            'callback' => $callback,
            'options' => $options
        ];
        Base::$route['last'] = ["delete" => $key];

        return $this;
    }

    /**
     * @param string $path
     * @param $callback
     * @param array $options
     * @return mixed
     */
    public function patch(string $path, $callback, array $options = []): self
    {
        $key = Base::$route['prefix'] . $path;
        Base::$route['patch'][$key] = [
            'callback' => $callback,
            'options' => $options
        ];
        Base::$route['last'] = ["patch" => $key];

        return $this;
    }

    /**
     * @param string $path
     * @param $callback
     * @param array $options
     * @return mixed
     */
    public function any(string $path, $callback, array $options = []): self
    {
        $this->post($path, $callback, $options);
        $this->put($path, $callback, $options);
        $this->delete($path, $callback, $options);
        $this->patch($path, $callback, $options);
        $this->get($path, $callback, $options);

        return $this;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function uri(int $id)
    {
        if ($id >= 0 and isset($this->uri[$id])) {
            return $this->uri[$id];
        } else {
            return false;
        }
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function name(string $name): self
    {
        $last = Base::$route['last'];
        $key = key($last);
        Base::$route[$key][$last[$key]]['options']['name'] = $name;

        return $this;
    }

    /**
     * @param string $filter
     * @return mixed
     */
    public function filter(string $filter): self
    {
        $last = Base::$route['last'];
        $key = key($last);
        Base::$route[$key][$last[$key]]['options']['filter'] = $filter;

        return $this;
    }

    /**
     * @param bool $status
     * @return mixed
     */
    public function cache(bool $status): self
    {
        $last = Base::$route['last'];
        $key = key($last);
        Base::$route[$key][$last[$key]]['options']['cache'] = $status;

        return $this;
    }

    /**
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function url(string $name, array $params = []): string
    {
        $route = null;
        $methods = ["get", "post", "put", "delete", "patch"];
        foreach ($methods as $method) {
            $route = array_key_first(array_filter(Base::$route[$method], function ($route) use ($name) {
                if (isset($route['options']['name'])) {
                    return $route['options']['name'] === $name;
                }
            }));
            if ($route != "") {
                break;
            }
        }

        return str_replace(array_keys($params), array_values($params), $route);
    }

    /**
     * @param $prefix
     * @return mixed
     */
    public function prefix($prefix): self
    {
        Base::$route['prefix'] = $prefix;

        return $this;
    }

    /**
     * @param \Closure $closure
     */
    public function group(\Closure $closure): void
    {
        $closure();
        Base::$route['prefix'] = '';
    }

    /**
     * @param $key
     * @param $pattern
     * @return mixed
     */
    public function where($key, $pattern): self
    {
        Base::$route['patterns'][':' . $key] = '(' . $pattern . ')';

        return $this;
    }

    /**
     * @param $from
     * @param $to
     * @param int $status
     */
    public function redirect($from, $to, int $status = 301)
    {
        Base::$route['get'][$from] = [
            'redirect' => $to,
            'status' => $status
        ];
    }

    /**
     * @param $to
     * @param $status
     */
    public function to($to, $status = 301): void
    {
        header("Location: " . $to, true, $status);
    }

    /**
     * @return mixed
     */
    public function dispatch()
    {
        $output = null;
        $executive = true;
        $method = $this->getMethod();
        $url = get_current_url_path();
        $cache_status = \Config\Cache::HTML['status'];

        $uri = trim($url, "/");
        if ($uri != "") {
            $this->uri = explode("/", $uri);
        }

        foreach (Base::$route[$method] as $path => $probs) {
            foreach (Base::$route['patterns'] as $key => $value) {
                $path = preg_replace('#' . $key . '#', $value, $path);
            }
            $pattern = '#^' . $path . '$#';
            if (preg_match($pattern, $url, $params)) {
                ob_start();
                Filters::prepare();
                Filters::global_route_filter($url);
                if (isset($probs["options"]["filter"])) {
                    Filters::route_filter($probs["options"]["filter"]);
                }
                $executive = Filters::before();
                array_shift($params);
                $this->hasRoute = true;
                if ($executive) {
                    if (isset($probs['redirect'])) {
                        $this->to($probs['redirect'], $probs['status']);
                    } else {
                        $callback = $probs['callback'];
                        if (is_string($callback)) {
                            $this->controller($callback, $params);
                        } elseif (is_callable($callback)) {
                            $this->callback($callback, $params);
                        }

                        if (isset($probs['options']['cache'])) {
                            $cache_status = $probs['options']['cache'];
                        }
                    }
                }
                $output = ob_get_clean();
                if (ob_get_length() > 0) {
                    ob_end_flush();
                }

                $executive = Filters::after();
            }
        }
        if ($this->hasRoute === false) {
            $this->hasRoute();
        }
        $this->route_current_define();
        if ($executive !== false) {
            $output .= $this->view();

            return ["output" => $output, "cache" => $cache_status];
        } else {
            Base::$views = [];

            return ["output" => $output, "cache" => $cache_status];
        }
    }

    /**
     * @param string $controller
     * @param $parameters
     */
    private function controller(string $controller, $parameters)
    {
        [$controllerName, $methodName] = explode('@', $controller);
        $controllerFilePath = APPLICATION_PATH . 'Controller/' . ucfirst($controllerName) . '.php';
        if (file_exists($controllerFilePath)) {
            $this->current = [
                "controller" => $controllerName,
                "cfunction" => $methodName,
                "params" => $parameters
            ];
            $controllerName = '\Application\Controller\\' . $controllerName;
            $controller = new $controllerName();
            if (!method_exists($controller, $methodName)) {
                $this->hasRoute = false;
            }
        } else {
            $this->hasRoute = false;
        }
        if ($this->hasRoute) {
            call_user_func_array([$controller, $methodName], $parameters);
        }
    }

    /**
     * @param $callback
     * @param $parameters
     */
    private function callback($callback, $parameters)
    {
        if (is_callable($callback)) {
            call_user_func_array($callback, $parameters);
            $this->current = [
                "controller" => '',
                "cfunction" => '',
                "params" => $parameters
            ];
        }
    }

    /**
     * @return mixed
     */
    private function view()
    {
        Base::$errorHandlerDie = false;
        $output = null;
        ob_start();
        $views = Base::$views;
        if (sizeof($views) > 0) {
            foreach ($views as $file => $data) {
                extract($data);
                require $file;
            }
        }
        $output = ob_get_clean();
        if (ob_get_length() > 0) {
            ob_end_flush();
        }
        Base::$views = [];

        return $output;
    }

    private function route_current_define()
    {
        define("CURRENT_CONTROLLER", $this->current['controller']);
        define("CURRENT_CFUNCTION", $this->current['cfunction']);
        define("CURRENT_CPARAMETERS", $this->current['params']);
    }

    private function hasRoute()
    {
        $params = [];
        $this->current = [
            "controller" => 'Error',
            "cfunction" => 'error_404',
            "params" => $params
        ];
        $methodName = 'error_404';
        $controller = new \Application\Controller\Error();
        call_user_func_array([$controller, $methodName], $params);
        echo $this->view();
        exit;
    }

}
