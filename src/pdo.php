<?php

namespace pulledbits\phuncql;

class pdo
{
    static function connect(\PDO $connection) : pdo\connect {
        return new pdo\connect($connection);
    }
}