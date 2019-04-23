<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

class phuncql
{
    static function parseQueries(string $rawQueries) : iterable
    {
        return explode(';', $rawQueries);
    }

    public static function connect(string $string)
    {
        return function(string $query) : \Traversable {
            return new class implements \Iterator {
                /**
                 * Return the current element
                 * @link https://php.net/manual/en/iterator.current.php
                 * @return mixed Can return any type.
                 * @since 5.0.0
                 */
                public function current()
                {
                    // TODO: Implement current() method.
                }

                /**
                 * Move forward to next element
                 * @link https://php.net/manual/en/iterator.next.php
                 * @return void Any returned value is ignored.
                 * @since 5.0.0
                 */
                public function next()
                {
                    // TODO: Implement next() method.
                }

                /**
                 * Return the key of the current element
                 * @link https://php.net/manual/en/iterator.key.php
                 * @return mixed scalar on success, or null on failure.
                 * @since 5.0.0
                 */
                public function key()
                {
                    // TODO: Implement key() method.
                }

                /**
                 * Checks if current position is valid
                 * @link https://php.net/manual/en/iterator.valid.php
                 * @return boolean The return value will be casted to boolean and then evaluated.
                 * Returns true on success or false on failure.
                 * @since 5.0.0
                 */
                public function valid()
                {
                    // TODO: Implement valid() method.
                }

                /**
                 * Rewind the Iterator to the first element
                 * @link https://php.net/manual/en/iterator.rewind.php
                 * @return void Any returned value is ignored.
                 * @since 5.0.0
                 */
                public function rewind()
                {
                    // TODO: Implement rewind() method.
                }
            };
        };
    }
}