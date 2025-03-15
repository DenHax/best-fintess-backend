<?php

namespace App\Core;

use Exception;

class Config
{
    private array $config = [];

    public function __construct(string $configPath)
    {
        if (!file_exists($configPath)) {
            throw new Exception("File {$configPath} doesn't exist");
        }

        $this->config = parse_ini_file($configPath, true);

        if ($this->config === false) {
            throw new Exception("Error to load config from {$configPath}");
        }
    }


    public function get(string $key)
    {
        $parts = explode('.', $key);
        if (count($parts) !== 2) {
            throw new Exception("Key must be in format 'section.key'");
        }

        [$section, $param] = $parts;

        if (!isset($this->config[$section][$param])) {
            throw new Exception("Key '{$key}' not found");
        }

        return $this->config[$section][$param];
    }

    public function getSection(string $section): array
    {
        if (!isset($this->config[$section])) {
            throw new Exception("Section '{$section}' not found");
        }

        return $this->config[$section];
    }

    public function getAll(): array
    {
        return $this->config;
    }
}
