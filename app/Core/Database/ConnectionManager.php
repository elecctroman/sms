<?php

declare(strict_types=1);

namespace App\Core\Database;

use App\Core\Config\ConfigRepository;
use PDO;
use PDOException;

class ConnectionManager
{
    /** @var array<string, PDO> */
    private array $connections = [];

    public function __construct(private readonly ConfigRepository $config)
    {
    }

    public function connection(?string $name = null): PDO
    {
        $name ??= $this->config->get('database.default', 'mysql');

        if (! isset($this->connections[$name])) {
            $this->connections[$name] = $this->createConnection($name);
        }

        return $this->connections[$name];
    }

    private function createConnection(string $name): PDO
    {
        /** @var array<string, mixed> $config */
        $config = $this->config->get("database.connections.{$name}");

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset'] ?? 'utf8mb4'
        );

        try {
            $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options'] ?? []);
        } catch (PDOException $exception) {
            throw new \RuntimeException('Database connection error: ' . $exception->getMessage(), 0, $exception);
        }

        return $pdo;
    }
}
