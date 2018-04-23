<?php

namespace pulledbits\phuncql;

class pdo
{
    static function prepare(string $rawQuery) : pdo\prepare {
        return new pdo\prepare($rawQuery);
    }
}