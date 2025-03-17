<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    /**
     * @param callable(Request): mixed $callback
     */
    public function get(string $route, callable $callback): void
    {
        $this->routes['GET'][$route] = $callback;
    }

    /**
     * @param callable(Request): mixed $callback
     */
    public function post(string $route, callable $callback): void
    {
        $this->routes['POST'][$route] = $callback;
    }

    public function resolve(): void
    {
        $request = new Request();
        $method = $request->getMethod();
        $path = $request->getPath();

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];

            if (is_array($callback)) {
                $obj = $callback[0];
                $methodName = $callback[1];
                $obj->$methodName($request);
            } else {
                $callback($request);
            }
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
