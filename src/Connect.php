<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

function connect(string $dsn, callable $error) : \Closure
{
    return call('pdo/connect', [])(...func_get_args());
}