<?php
declare(strict_types=1);

namespace pulledbits\phuncql\pdo;

use function pulledbits\phuncql\call;

return static function (array $links) {
    /**
     * @impure connection with database through PDO
     * @return callable
     */
    return static function (string $dsn, callable $error) use ($links) : callable {
        if (array_key_exists($dsn, $links) === false) {
            try {
                $links[$dsn] = new \PDO($dsn);
            } catch (\PDOException $e) {
                $error(new \Error("Unable to connect: " . $e->getMessage()));
                return static function (string $query) use ($error) : callable {
                    trigger_error('No connection', E_USER_ERROR);
                };
            }
        }
        $connection = $links[$dsn];
        return static function (string $query) use ($connection, $error) : callable {
            return call('pdo/prepare', $connection->prepare($query), $error);
        };
    };
};