<?php

require 'vendor/autoload.php';

use Docker\Docker;

$docker = new Docker();

$containers = $docker->getContainerManager()->findAll();
$validImageNames = ['mariadb:.+', 'mysql:.+'];
$cfg = [];

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

    $cfg['Servers'][] = [
        'auth_type' => 'cookie',
        'host' => $ipAddress,
        'verbose' => $containerName . ' (' . $ipAddress . ')',
        'connect_type' => 'tcp',
        'compress' => false,
        'AllowNoPassword' => false
    ];
}
