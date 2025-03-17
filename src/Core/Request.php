<?php

namespace App\Core;

class Request
{
    private array $queryParams;
    private array $bodyParams;

    public function __construct()
    {
        $this->queryParams = $_GET;
        $this->bodyParams = $this->parseBody();
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public function getPath(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

        // Убираем префикс /api если есть
        if (strpos($path, '/api') === 0) {
            $path = substr($path, 4);
        }

        return $path;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getBodyParams(): array
    {
        return $this->bodyParams;
    }

    private function parseBody(): array
    {
        if ($this->getMethod() === 'POST') {
            return $_POST;
        }

        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }
}
