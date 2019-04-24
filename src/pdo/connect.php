<?php
declare(strict_types=1);

namespace pulledbits\phuncql\pdo;

use pulledbits\phuncql\pdo;

return static function (string $dsn, callable $error): callable {
    if (array_key_exists($dsn, pdo::$links) === false) {
        try {
            pdo::$links[$dsn] = new \PDO($dsn);
        } catch (\PDOException $e) {
            $error(new \Error("Unable to connect: " . $e->getMessage()));
            return static function (string $query) use ($error) : callable {
                trigger_error('No connection', E_USER_ERROR);
            };
        }
    }
    $connection = pdo::$links[$dsn];
    return static function (string $query) use ($connection, $error) : callable {
        return pdo::prepare($connection->prepare($query), $error);
    };
};
