<?php

namespace pulledbits\phuncql;

/**
 * Class pdo
 * @package pulledbits\phuncql
 * @impure connection with database through PDO
 */
class pdo
{
    static $links = [];

    static function connect(string $dsn) : \Closure{
        if (array_key_exists($dsn, self::$links) === false) {
            self::$links[$dsn] = new \PDO($dsn);
        }
        $connection = self::$links[$dsn];
        return function(string $rawQuery) use ($connection) : \Closure {
            return self::prepare($connection->prepare($rawQuery));
        };
    }

    private static function prepare(\PDOStatement $statement) : \Closure {
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