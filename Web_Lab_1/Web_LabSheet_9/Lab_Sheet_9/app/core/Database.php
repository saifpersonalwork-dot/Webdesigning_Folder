<?php
declare(strict_types=1);

final class Database {
    private static ?PDO $connection = null;
    private function __construct() {}

    public static function instance(array $config): PDO {
        if (self::$connection === null) {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s',
                $config['host'], $config['name'], $config['charset']);
            self::$connection = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }
        return self::$connection;
    }
}
