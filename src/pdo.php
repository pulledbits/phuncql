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

    public static function connect(string $dsn, callable $error): callable
    {
        return import(__DIR__ . DIRECTORY_SEPARATOR . 'pdo' . DIRECTORY_SEPARATOR . 'connect.php', self::$links)(...func_get_args());
    }

    public static function prepare(\PDOStatement $statement, callable $error): callable
    {
        return static function(...$parameters) use ($statement, $error) : callable {
            try {
                $statement->execute(...$parameters);
            } catch (\PDOException $exception) {
                return function(callable $callback) use ($exception, $error) : bool {
                    $error(new \Error($exception->getMessage()));
                    return false;
                };
            }
            return static function(callable $callback) use ($statement) : bool {
                return $statement->fetchAll(\PDO::FETCH_FUNC, $callback) !== false;
            };
        };
    }
}