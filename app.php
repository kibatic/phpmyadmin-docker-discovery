<?php

require __DIR__ . '/vendor/autoload.php';

$dockerDiscovery = new \Kibatic\DockerDiscovery\DockerDiscovery();

$containers = $dockerDiscovery->discover(
    \Kibatic\DockerDiscovery\DockerDiscovery::FILTER_IMAGE,
    ['mariadb:.+', 'mysql:.+']
);

$cfg = [];
$i = 1;

foreach ($containers as $container) {
    $cfg['Servers'][$i] = [
        'host' => $container->ipAddresses[0],
        'verbose' => $container->name,
        'connect_type' => 'tcp',
        'compress' => false
    ];

    $i++;
}

var_dump($cfg);
