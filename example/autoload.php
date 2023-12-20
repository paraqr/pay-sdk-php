<?php

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    /** @var \Composer\Autoload\ClassLoader $loader */
    $loader = require __DIR__ . '/../vendor/autoload.php';
} else {
    spl_autoload_register(function ($class) {
        $class = str_replace('paraqr\\payment\\', 'src/', $class);
        $file = __DIR__ . '/../' . $class . '.php';
        require_once $file;
    });
}