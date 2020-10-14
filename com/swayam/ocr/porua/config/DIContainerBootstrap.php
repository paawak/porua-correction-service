<?php

/**
 * The bootstrap file creates and returns the container.
 */
use DI\ContainerBuilder;

require __DIR__ . '/../../../../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(__DIR__ . '/DIConfiguration.php');

$customConfiguration = $_SERVER['HTTP_PORUA_APPLICATION_CONFIG'];

if (($_SERVER['SERVER_NAME'] == 'ocrservice.paawak.me') 
        && isset($customConfiguration)) {
    $containerBuilder->addDefinitions('/home/users/web/b2928/ipg.paawak20736/dev/RemoteApplicationConfig.php');
} else {
    $containerBuilder->addDefinitions(__DIR__ . '/LocalApplicationConfig.php');
}

$container = $containerBuilder->build();

return $container;
