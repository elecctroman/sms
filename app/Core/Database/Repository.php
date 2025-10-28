<?php

declare(strict_types=1);

namespace App\Core\Database;

use PDO;
use PDOStatement;

abstract class Repository
{
    protected PDO $connection;

    public function __construct(ConnectionManager $manager)
    {
        $this->connection = $manager->connection();
    }

    protected function query(string $sql, array $params = []): PDOStatement
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);

        return $statement;
    }

    protected function transaction(callable $callback): mixed
    {
        try {
            $this->connection->beginTransaction();
            $result = $callback($this->connection);
            $this->connection->commit();
            return $result;
        } catch (\Throwable $throwable) {
            $this->connection->rollBack();
            throw $throwable;
        }
    }
}
