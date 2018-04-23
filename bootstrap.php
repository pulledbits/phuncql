<?php
declare(strict_types=1);

namespace pulledbits\phuncql;

function loadModule(string $moduleNamespace, string $modulePath) : void {
    spl_autoload_register(function(string $class) use ($moduleNamespace, $modulePath) : void {
        if (strpos($class, $moduleNamespace) === 0) {
            include_once $modulePath . str_replace('\\', DIRECTORY_SEPARATOR, str_replace($moduleNamespace, '', $class)) . '.php';
        }
    });
    include_once $modulePath . '.php';
}

function import(string $moduleIdentifier) {
    spl_autoload_register(function(string $class) use ($moduleIdentifier) {
        if (str_replace(__NAMESPACE__ . '\\', '', $class) === $moduleIdentifier) {
            loadModule(__NAMESPACE__ . '\\' . $moduleIdentifier, __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $moduleIdentifier);
        }
    });
}

import('phuncql');
