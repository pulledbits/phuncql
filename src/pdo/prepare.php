<?php
declare(strict_types = 1);

namespace pulledbits\phuncql\pdo;


class prepare
{
    private $statement;

    public function __construct(\PDOStatement $statement) {
        $this->statement = $statement;
    }
    public function __invoke(...$functionArguments) : array {
        if (count($functionArguments) === 0) {
            $result = $this->statement->execute();
        } elseif (is_array($functionArguments[0])) {
            $result = $this->statement->execute($functionArguments[0]);
        } else {
            $result = $this->statement->execute($functionArguments);
        }
        if ($result) {
            return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
        }
        return [];
    }
}