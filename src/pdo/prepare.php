<?php
declare(strict_types=1);

namespace pulledbits\phuncql\pdo;

class prepare {
    private $rawQuery;

    public function __construct(string $rawQuery) {
        $this->rawQuery = $rawQuery;
    }
    public function __invoke(\PDO $connection) : array {
        $statement = $connection->prepare($this->rawQuery);
        if ($statement->execute() === false) {
            return [];
        }
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}