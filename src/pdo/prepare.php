<?php

namespace pulledbits\phuncql\pdo;


class prepare
{
    private $statement;

    public function __construct(\PDOStatement $statement) {
        $this->statement = $statement;
    }
    public function __invoke() : array {
        if ($this->statement->execute() === false) {
            return [];
        }
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}