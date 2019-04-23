<?php

namespace pulledbits\phuncql;

class pdo
{
    static function connect(\PDO $connection) : \Closure{
        return function(string $rawQuery) use ($connection) : \Closure {
            return self::prepare($connection->prepare($rawQuery));
        };
    }
    static function prepare(\PDOStatement $statement) : \Closure {
        return function (...$functionArguments) use ($statement) : array {
            if (count($functionArguments) === 0) {
                $result = $statement->execute();
            } elseif (is_array($functionArguments[0])) {
                $result = $statement->execute($functionArguments[0]);
            } else {
                $result = $statement->execute($functionArguments);
            }
            if ($result) {
                return $statement->fetchAll(\PDO::FETCH_ASSOC);
            }
            return [];
        };
    }
}