<?php

// src/connect.php

$config = parse_ini_file('./config.ini', true);


// Получаем данные конфигурации
$dbHost = $config['database']['DB_HOST'];
$dbUsername = $config['database']['DB_USER'];
$dbPassword = $config['database']['DB_PASS'];
$dbName = $config['database']['DB_NAME'];

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
