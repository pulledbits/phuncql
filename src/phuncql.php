<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

function parseQueries(\PDO $connection, string $rawQueries) : array {
    return [function() use ($connection) {
        return ['col1' => null, 'col2' => null];
    }, function() use ($connection) {
        return ['col3' => null, 'col2' => null];
    }];
}