<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

use function iter\rewindable\map;

function parseQueries(string $rawQueries) : iterable
{
    $parseQuery = function (string $rawQuery) {
        return function (\PDO $connection) use ($rawQuery) : array
        {
            $statement = $connection->prepare($rawQuery);
            if ($statement->execute() === false) {
                return [];
            }
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        };
    };
    return map($parseQuery, explode(';', $rawQueries));
}