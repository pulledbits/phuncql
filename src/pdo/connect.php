<?php
declare(strict_types=1);

namespace pulledbits\phuncql\pdo;

class connect {
    private $connection;

    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }
    public function __invoke(string $rawQuery) : prepare {
        return new prepare($this->connection->prepare($rawQuery));
    }
}