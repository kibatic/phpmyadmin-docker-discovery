<?php

require __DIR__ . '/vendor/autoload.php';

use Docker\Docker;

$docker = new Docker();

$containers = $docker->getContainerManager()->findAll();
$validImageNames = ['mariadb:.+', 'mysql:.+'];
$cfg = [];

$i = 1;

function isDatabase($container, $validImageNames)
{
    foreach ($validImageNames as $validImageName) {
        if (preg_match('/' . $validImageName . '/', $container->getImage())) {
            return true;
        }
    }

    return false;
}

foreach ($containers as $container) {
    if (!isDatabase($container, $validImageNames)) {
      continue;
    }

    $containerName = str_replace('/', '', $container->getNames()[0]);

    $network = (array) $container->getNetworkSettings()->getNetworks();
    $ipAddress = array_shift($network)->getIpAddress();

    $cfg['Servers'][$i] = [
        'host' => $ipAddress,
        'verbose' => $containerName,
        'connect_type' => 'tcp',
        'compress' => false
    ];

    $i++;
}
