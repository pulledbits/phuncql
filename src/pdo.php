<?php

namespace pulledbits\phuncql;

/**
 * Class pdo
 * @package pulledbits\phuncql
 * @impure connection with database through PDO
 */
class pdo
{
    static $links = [];

    static function connect(string $dsn): \Closure
    {
        if (array_key_exists($dsn, self::$links) === false) {
            try {
                self::$links[$dsn] = new \PDO($dsn);
            } catch (\PDOException $e) {
                trigger_error("Unable to connect: " . $e->getMessage(), E_USER_ERROR);
            }
        }
        $connection = self::$links[$dsn];
        return function (string $rawQuery) use ($connection) : \Closure {
            return self::prepare($connection->prepare($rawQuery));
        };
    }

    private static function prepare(\PDOStatement $statement): \Closure
    {
        return function(...$parameters) use ($statement) : \Traversable {
            return new class($statement, $parameters) implements \Iterator
            {
                private $current;
                private $statement;
                private $parameters;

                public function __construct(\PDOStatement $statement, array $parameters)
                {
                    $this->statement = $statement;
                    $this->parameters = $parameters;
                    $this->rewind();
                    $this->next();
                }

                /**
                 * Return the current element
                 * @link https://php.net/manual/en/iterator.current.php
                 * @return mixed Can return any type.
                 * @since 5.0.0
                 */
                public function current()
                {
                    return $this->current;
                }

                /**
                 * Move forward to next element
                 * @link https://php.net/manual/en/iterator.next.php
                 * @return void Any returned value is ignored.
                 * @since 5.0.0
                 */
                public function next() : void
                {
                    $this->current = $this->statement->fetch(\PDO::FETCH_ASSOC);
                }

                /**
                 * Return the key of the current element
                 * @link https://php.net/manual/en/iterator.key.php
                 * @return mixed scalar on success, or null on failure.
                 * @since 5.0.0
                 */
                public function key()
                {
                    return null;
                }

                /**
                 * Checks if current position is valid
                 * @link https://php.net/manual/en/iterator.valid.php
                 * @return boolean The return value will be casted to boolean and then evaluated.
                 * Returns true on success or false on failure.
                 * @since 5.0.0
                 */
                public function valid() : bool
                {
                    return $this->current !== false;
                }

                /**
                 * Rewind the Iterator to the first element
                 * @link https://php.net/manual/en/iterator.rewind.php
                 * @return void Any returned value is ignored.
                 * @since 5.0.0
                 */
                public function rewind() : void
                {
                    $this->statement->execute(...$this->parameters);
                }
            };
        };
    }
}