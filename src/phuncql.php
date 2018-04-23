<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

use function iter\rewindable\map;

function activateModule(string $moduleIdentifier) : void {
    spl_autoload_register(moduleAutoloader(__NAMESPACE__ . '\\' . $moduleIdentifier, __DIR__ . DIRECTORY_SEPARATOR . $moduleIdentifier));
    include_once __DIR__ . DIRECTORY_SEPARATOR . $moduleIdentifier . '.php';
}

function moduleAutoloader(string $moduleNamespace, string $modulePath) {
    return function(string $class) use ($moduleNamespace, $modulePath) : void {
        if (strpos($class, $moduleNamespace) !== false) {
            include_once $modulePath . str_replace('\\', DIRECTORY_SEPARATOR, str_replace($moduleNamespace, '', $class)) . '.php';
        }
    };
}

spl_autoload_register(function(string $class) {
    if (strpos($class, __NAMESPACE__) === false) {
        return;
    }
    $module = substr($class, strlen(__NAMESPACE__));
    $nsSeparatorPosition = strpos($module, '\\', 1);
    if ($nsSeparatorPosition === false) {
        activateModule(substr($module, strlen('\\')));
    }
});


function parseQueries(string $rawQueries) : iterable
{
    $parseQuery = function (string $rawQuery) {
        return pdo::prepare($rawQuery);
    };
    return map($parseQuery, explode(';', $rawQueries));
}