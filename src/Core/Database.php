<?php

$config = parse_ini_file('./config.ini', true);

use PDO;
use PDOException;

$dbHost = $config['database']['DB_HOST'];
$dbUsername = $config['database']['DB_USER'];
$dbPassword = $config['database']['DB_PASS'];
$dbName = $config['database']['DB_NAME'];

error_log($dbHost);

try {
    $pdo = new PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Success connection to db");
} catch (PDOException $e) {
    $errorStr = "Connection failed: " . $e->getMessage();
    error_log($errorStr);
}
