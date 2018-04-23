<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

function parseQueries(string $rawQueries) : array {
    return array_map(function(string $rawQuery) {
        return function(\PDO $connection) use ($rawQuery) : array {
            $statement = $connection->prepare($rawQuery);
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        };
    }, explode(';', $rawQueries));
}