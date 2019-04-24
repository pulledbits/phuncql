<?php
declare(strict_types=1);

namespace pulledbits\phuncql\pdo;

return function(\PDOStatement $statement, callable $error) {
    /**
     * @impure connection with database through PDO
     * @return callable
     */
    return function(array $parameters = []) use ($statement, $error) : callable {
        $parameterCount = preg_match_all('/(\?|:\w+)/', $statement->queryString);
        if ($parameterCount > count($parameters)) { // do this ourself, since sqlite does not complain about missing parameters
            return function(callable $callback) use ($error) : bool {
                $error(new \Error('unable to execute query: insufficient parameters bound'));
                return false;
            };
        }

        $statement->execute($parameters);
        return function(callable $callback) use ($statement) : bool {
            return $statement->fetchAll(\PDO::FETCH_FUNC, $callback) !== false;
        };
    };
};