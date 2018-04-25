<?php
declare(strict_types=1);

namespace pulledbits\phuncql\pdo;

class connect {
    private $connection;

    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }
    public function __invoke(string $rawQuery) : array {
        $statement = $this->connection->prepare($rawQuery);
        if ($statement->execute() === false) {
            return [];
        }
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}