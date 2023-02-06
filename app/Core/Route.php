<?php
namespace App\Core;

class Route
{

    public static array $routes = [];
    public static string $prefix = '';
    public static bool $hasRoute = false;
    public static array $patterns = [
        ':id[0-9]?' => '([0-9]+)',
        ':url[0-9]?' => '([0-9a-zA-Z-_]+)'
    ];


    /**
     * @param string $path
     * @param $callback
     * @return Route
     */
    public static function get(string $path, $callback): Route
    {
        self::$routes['get'][self::$prefix . $path] = [
            'callback' => $callback
        ];
        return new self();
    }

    public static function dispatch(): void
    {
        $url = self::getUrl();
        $method = self::getMethod();
        foreach (self::$routes[$method] as $path => $props) {
            foreach (self::$patterns as $key => $pattern) {
                $path = preg_replace('#' . $key . '#', $pattern, $path);
            }
            $pattern = '#^' . $path . '$#';

            if (preg_match($pattern, $url, $params)) {

                self::$hasRoute = true;
                array_shift($params);


                $callback = $props['callback'];

                if (is_callable($callback)) {
                    echo call_user_func_array($callback, $params);
                } elseif (is_string($callback)) {

                    [$controllerName, $methodName] = explode('@', $callback);

                    $controllerName = '\App\Controllers\\' . $controllerName;
                    $controller = new $controllerName();
                    echo call_user_func_array([$controller, $methodName], $params);

                }


            }
        }
        self::hasRoute();
    }

    public static function hasRoute(): void
    {
        if (self::$hasRoute === false) {
            die('Page not found');
        }
    }

    /**
     * @return string
     */
    public static function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return string
     */
    public static function getUrl(): string
    {
        return str_replace(getenv('BASE_PATH'), '', $_SERVER['REQUEST_URI']);
    }


    /**
     * @param string $name
     * @param array $params
     * @return string
     */
    public static function url(string $name, array $params = []): string
    {
        $route = array_key_first(array_filter(self::$routes['get'], function ($route) use ($name) {
            return isset($route['name']) && $route['name'] === $name;
        }));
        return getenv('BASE_PATH') . str_replace(array_map(fn($key) => ':' . $key, array_keys($params)), array_values($params), $route);
    }


    public static function redirect($from, $to, $status = 301): void
    {
        self::$routes['get'][$from] = [
            'redirect' => $to,
            'status' => $status
        ];
    }


}