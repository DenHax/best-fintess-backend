<?php

namespace App\Core;

use Exception;

class Config
{
    private array $config = [];

    public function __construct(string $configPath)
    {
        if (!file_exists($configPath)) {
            throw new Exception("Файл конфигурации не найден: {$configPath}");
        }

        $this->config = parse_ini_file($configPath, true);

        if ($this->config === false) {
            throw new Exception("Не удалось загрузить конфигурацию из файла: {$configPath}");
        }
    }


    public function get(string $key)
    {
        $parts = explode('.', $key);
        if (count($parts) !== 2) {
            throw new Exception("Ключ должен быть в формате 'section.key'");
        }

        [$section, $param] = $parts;

        if (!isset($this->config[$section][$param])) {
            throw new Exception("Ключ '{$key}' не найден в конфигурации");
        }

        return $this->config[$section][$param];
    }

    public function getSection(string $section): array
    {
        if (!isset($this->config[$section])) {
            throw new Exception("Секция '{$section}' не найдена в конфигурации");
        }

        return $this->config[$section];
    }

    public function getAll(): array
    {
        return $this->config;
    }
}
