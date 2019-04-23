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

    static function connect(string $dsn, callable $error): callable
    {
        if (array_key_exists($dsn, self::$links) === false) {
            try {
                self::$links[$dsn] = new \PDO($dsn);
            } catch (\PDOException $e) {
                $error(new \Error("Unable to connect: " . $e->getMessage()));
                return function(string $query) use ($error) : callable {
                    trigger_error('No connection', E_USER_ERROR);
                };
            }
        }
        $connection = self::$links[$dsn];
        return function (string $query) use ($connection, $error) : callable {
            return self::prepare($connection->prepare($query), $error);
        };
    }

    private static function prepare(\PDOStatement $statement, callable $error): callable
    {
        return function(...$parameters) use ($statement, $error) : callable {
            try {
                $statement->execute(...$parameters);
            } catch (\PDOException $exception) {
                return function(callable $callback) use ($exception, $error) : bool {
                    $error(new \Error($exception->getMessage()));
                    return false;
                };
            }
            return function(callable $callback) use ($statement) : bool {
                return $statement->fetchAll(\PDO::FETCH_FUNC, $callback) !== false;
            };
        };
    }
}