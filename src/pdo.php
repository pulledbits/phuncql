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
        return call('pdo/connect', self::$links)(...func_get_args());
    }
}