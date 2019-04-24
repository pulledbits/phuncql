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
        return import(__DIR__ . DIRECTORY_SEPARATOR . 'pdo' . DIRECTORY_SEPARATOR . 'prepare.php', $statement, $error);
    }
}