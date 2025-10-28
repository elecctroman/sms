<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

abstract class Model
{
    private static ?PDO $connection = null;

    protected function db(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = Config::get('database');
        if (!is_array($config)) {
            throw new PDOException('Database configuration missing.');
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            (string) ($config['host'] ?? 'localhost'),
            (int) ($config['port'] ?? 3306),
            (string) ($config['database'] ?? ''),
            (string) ($config['charset'] ?? 'utf8mb4')
        );

        self::$connection = new PDO(
            $dsn,
            (string) ($config['username'] ?? ''),
            (string) ($config['password'] ?? ''),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        return self::$connection;
    }
}
