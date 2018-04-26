<?php
namespace pulledbits\phuncql;

class queries
{
    private $queries;

    public function __construct(array $queries) {
        $this->queries = $queries;
    }
    public function __invoke(callable $callback) : void {
        foreach ($this->queries as $query) {
            $callback($query);
        }
    }
}