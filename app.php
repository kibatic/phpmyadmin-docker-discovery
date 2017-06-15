<?php

require 'vendor/autoload.php';

use Docker\Docker;

$docker = new Docker();

$containers = $docker->getContainerManager()->findAll();
$validImageNames = ['mariadb:.+', 'mysql:.+'];
$cfg = [];

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

    $cfg['Servers'][] = [
        'auth_type' => 'cookie',
        'host' => $ipAddress,
        'verbose' => $containerName . ' (' . $ipAddress . ')',
        'connect_type' => 'tcp',
        'compress' => false,
        'AllowNoPassword' => false
    ];
}
