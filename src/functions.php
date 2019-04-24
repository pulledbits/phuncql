<?php
declare(strict_types=1);
namespace pulledbits\phuncql;

function import(string $path) : callable {
    static $cache = [];
    if (array_key_exists($path, $cache) === false) {
        $cache[$path] = require $path;
    }
    return $cache[$path](...array_slice(func_get_args(), 1));
}