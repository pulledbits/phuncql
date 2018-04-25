<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

use function iter\rewindable\map;

import('pdo');

class phuncql
{
    static function parseQueries(callable $callback, string $rawQueries): iterable
    {
        return map($callback, explode(';', $rawQueries));
    }
}