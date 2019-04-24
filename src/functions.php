<?php
declare(strict_types=1);
namespace pulledbits\phuncql;

function import(string $path, array $context) : callable {
    static $cache = [];
    if (array_key_exists($path, $cache) === false) {
        extract($context, EXTR_SKIP);
        $cache[$path] = require $path;
    }
    return $cache[$path];
}