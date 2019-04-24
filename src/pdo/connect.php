<?php
declare(strict_types=1);

namespace pulledbits\phuncql\pdo;

use function pulledbits\phuncql\call;


return function () {
    /**
     * @impure connection with database through PDO
     * @return callable
     */
    return static function (string $dsn, callable $error) : callable {
        try {
            $connection = new \PDO($dsn);
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            $error(new \Error("Unable to connect: " . $e->getMessage()));
            return static function (string $query) use ($error) : void {};
        }

        return static function (string $query) use ($connection, $error) : callable {
            try {
                return call('pdo/prepare', $connection->prepare($query), $error);
            } catch (\PDOException $exception) {
                $error(new \Error($exception->getMessage()));
            }
            return static function() : void {};
        };
    };
};