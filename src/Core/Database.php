<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;
    public static function connect(array $db_conn_info): PDO
    {
        if (self::$pdo === null) {

            $dsn = "pgsql:host={$db_conn_info['DB_HOST']};dbname={$db_conn_info['DB_NAME']}";
            $db_pass = $db_conn_info["DB_PASS"];
            $db_user = $db_conn_info["DB_USER"];
            try {
                self::$pdo = new PDO($dsn, $db_user, $db_pass);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                error_log("Success connection to db");
            } catch (PDOException $e) {
                $errorStr = "Connection failed: " . $e->getMessage();
                error_log($errorStr);
            }
        }
        return self::$pdo;
    }
}
