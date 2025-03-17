<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    /**
     * @param callable(): mixed $callback
     */
    public function get(string $route, callable $callback): void
    {
        $this->routes['GET'][$route] = $callback;
    }
    /**
     * @param callable(): mixed $callback
     */
    public function post(string $route, callable $callback): void
    {
        $this->routes['POST'][$route] = $callback;
    }

    public function resolve(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (strpos($path, '/api') === 0) {
            $path = substr($path, 4);
        }

        if (isset($this->routes[$method][$path])) {
            call_user_func($this->routes[$method][$path]);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
