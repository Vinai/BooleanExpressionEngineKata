<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'BooleanExpressionEngine\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $classFile = str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    $file = __DIR__ . '/src/' . $classFile;
    if (file_exists($file)) {
        require $file;
    }
}, false, true);
