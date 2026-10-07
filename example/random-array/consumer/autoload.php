<?php

/** @var Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__ . '/../../../vendor/autoload.php';
$loader->addPsr4('RandomArrayConsumer\\', __DIR__ . '/src');
$loader->addPsr4('RandomArrayConsumer\\Tests\\', __DIR__ . '/tests');
