<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

function parseQueries(string $rawQueries) : array {
    return [function(\PDO $connection) : array {
        $statement = $connection->prepare('SELECT col1, col2 FROM table');
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }, function(\PDO $connection)  : array {
        $statement = $connection->prepare('SELECT col3, col2 FROM table');
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }];
}