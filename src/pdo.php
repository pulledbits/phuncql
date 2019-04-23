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

    static function connect(string $dsn): callable
    {
        if (array_key_exists($dsn, self::$links) === false) {
            try {
                self::$links[$dsn] = new \PDO($dsn);
            } catch (\PDOException $e) {
                trigger_error("Unable to connect: " . $e->getMessage(), E_USER_ERROR);
            }
        }
        $connection = self::$links[$dsn];
        return function (string $rawQuery) use ($connection) : callable {
            return self::prepare($connection->prepare($rawQuery));
        };
    }

    private static function prepare(\PDOStatement $statement): callable
    {
        return function(...$parameters) use ($statement) : callable {
            try {
                $statement->execute(...$parameters);
            } catch (\PDOException $exception) {
                return function(callable $callback) use ($exception) : bool {
                    $callback(['error' => $exception->getMessage()]);
                    return false;
                };
            }
            return function(callable $callback) use ($statement) : bool {
                return $statement->fetchAll(\PDO::FETCH_FUNC, $callback) !== false;
            };
        };
    }
}