<?php

namespace pulledbits\phuncql;

class pdo
{
    static $links = [];

    public static function connect(string $dsn, callable $error): callable
    {
        return call('pdo/connect', self::$links)(...func_get_args());
    }
}