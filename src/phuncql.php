<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

use function iter\rewindable\map;

import('pdo');

class phuncql
{
    static function parseQueries(string $rawQueries): iterable
    {
        $parseQuery = function (string $rawQuery) {
            return pdo::prepare($rawQuery);
        };
        return map($parseQuery, explode(';', $rawQueries));
    }
}