<?php

require __DIR__ . '/vendor/autoload.php';

use Docker\Docker;

$docker = new Docker();

$containers = $docker->getContainerManager()->findAll();
$validImageNames = ['mariadb:.+', 'mysql:.+'];
$cfg = [];

$i = 1;

foreach ($containers as $container) {
    foreach ($validImageNames as $validImageName) {
        if (preg_match('/' . $validImageName . '/', $container->getImage())) {
            break;
        }

        continue 2;
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
