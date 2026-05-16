<?php
class Conexao {
    private static $instance;

    public static function getConn() {
        if (!isset(self::$instance)) {
            $host   = getenv('MYSQL_HOST')     ?: 'mysql';
            $db     = getenv('MYSQL_DATABASE') ?: 'topcar';
            $user   = getenv('MYSQL_USERNAME') ?: 'mysql';
            $pass   = getenv('MYSQL_PASSWORD') ?: 'pass3word';

            self::$instance = new \PDO(
                "mysql:host=$host;dbname=$db;charset=utf8mb4",
                $user,
                $pass
            );
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->exec("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
        }
        return self::$instance;
    }
}
