<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

use function iter\rewindable\map;

preloadModule('pdo');

class phuncql
{
    function parseQueries(string $rawQueries): iterable
    {
        $parseQuery = function (string $rawQuery) {
            return pdo::prepare($rawQuery);
        };
        return map($parseQuery, explode(';', $rawQueries));
    }
}