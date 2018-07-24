<?php

namespace ApplicationTest;

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

return [
    'elasticsearch' => [
        'hosts' => [
            'elastic:changeme@elasticsearch1:9200',
        ],
    ],
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\\DBAL\\Driver\\PDOSqlite\\Driver',
                'params' => [
                    'memory' => true,
                ],
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\ORM\Mapping\Driver\DriverChain::class,
            ],
        ],
        'migrations_configuration' => [
            'orm_default' => [
                \directory::class => __DIR__ . '/../module/Db/src/Db/Migrations',
                'name' => 'Doctrine Database Migrations',
                'namespace' => 'Db\\Migrations',
                'table' => 'Migrations',
                'column' => 'version',
            ],
        ],
    ],
];
