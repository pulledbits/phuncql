<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

use function iter\rewindable\map;

spl_autoload_register(function(string $class) {
    if (strpos($class, __NAMESPACE__) === false) {
        return;
    }
    $subclass = substr($class, strlen(__NAMESPACE__));
    $module = substr($subclass, strlen('\\'), strpos($subclass, '\\', 1) - 1);
    switch ($module) {
        case 'pdo':
            require __DIR__ . DIRECTORY_SEPARATOR . 'pdo' . DIRECTORY_SEPARATOR . 'prepare.php';
            break;
    }
    return;
});


function parseQueries(string $rawQueries) : iterable
{
    $parseQuery = function (string $rawQuery) {
        return new pdo\prepare($rawQuery);
    };
    return map($parseQuery, explode(';', $rawQueries));
}