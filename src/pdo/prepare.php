<?php
declare(strict_types = 1);

namespace pulledbits\phuncql\pdo;


class prepare
{
    private $statement;

    public function __construct(\PDOStatement $statement) {
        $this->statement = $statement;
    }
    public function __invoke() : array {
        $parameterCount = preg_match_all('/:\w+/', $this->statement->queryString,$matches);
        if ($parameterCount === 0) {

        } elseif ($parameterCount > func_num_args()) {
            throw new \ArgumentCountError($parameterCount . ' parameter(s) expected, ' . func_num_args() . ' given.');
        }
        if ($this->statement->execute() === false) {
            return [];
        }
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}