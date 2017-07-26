<?php

require __DIR__ . '/vendor/autoload.php';

$dockerDiscovery = new \Kibatic\DockerDiscovery\DockerDiscovery();

$containers = $dockerDiscovery->discover(['mariadb:.+', 'mysql:.+']);

$cfg = [];
$i = 0;

foreach ($containers as $container) {
    $cfg['Servers'][$i] = [
        'host' => $container['ip'],
        'verbose' => $container['name'],
        'connect_type' => 'tcp',
        'compress' => false
    ];

    $i++;
}
