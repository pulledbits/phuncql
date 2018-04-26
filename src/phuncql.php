<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

class phuncql
{
    static function parseQueries(string $rawQueries) : iterable
    {
        return explode(';', $rawQueries);
    }
}