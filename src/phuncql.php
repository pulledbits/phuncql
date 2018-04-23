<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

use function iter\rewindable\map;

spl_autoload_register(function(string $class) {
    if (strpos($class, __NAMESPACE__) === false) {
        return;
    }
    $module = substr($class, strlen(__NAMESPACE__));
    $nsSeparatorPosition = strpos($module, '\\', 1);
    if ($nsSeparatorPosition === false) {
        require_once __DIR__ . DIRECTORY_SEPARATOR . substr($module, strlen('\\')) . '.php';
        return;
    }

    switch (substr($module, strlen('\\'), $nsSeparatorPosition - 1)) {
        case 'pdo':
            require_once __DIR__ . DIRECTORY_SEPARATOR . 'pdo' . DIRECTORY_SEPARATOR . 'prepare.php';
            break;
    }
    return;
});


function parseQueries(string $rawQueries) : iterable
{
    $parseQuery = function (string $rawQuery) {
        return pdo::prepare($rawQuery);
    };
    return map($parseQuery, explode(';', $rawQueries));
}