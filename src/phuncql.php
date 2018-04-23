<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

function parseQueries(string $rawQueries) : array {
    return [function(\PDO $connection){
        return ['col1' => null, 'col2' => null];
    }, function(\PDO $connection){
        return ['col3' => null, 'col2' => null];
    }];
}