<?php
declare(strict_types=1);

namespace pulledbits\phuncql\pdo;

return static function(\PDOStatement $statement, callable $error) {
    /**
     * @impure connection with database through PDO
     * @return callable
     */
    return static function(...$parameters) use ($statement, $error) : callable {
        try {
            $statement->execute(...$parameters);
        } catch (\PDOException $exception) {
            return function(callable $callback) use ($exception, $error) : bool {
                $error(new \Error($exception->getMessage()));
                return false;
            };
        }
        return static function(callable $callback) use ($statement) : bool {
            return $statement->fetchAll(\PDO::FETCH_FUNC, $callback) !== false;
        };
    };
};