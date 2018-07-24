<?php

namespace Console;

return [
    'service_manager' => [
        'invokables' => [
            Rule\ShortStats::class => Rule\ShortStats::class,
            Rule\HighTraffic::class => Rule\HighTraffic::class,
        ]
    ],

    'controllers' => [
        'factories' => [
            Controller\HttpTrafficController::class   => Controller\HttpTrafficControllerFactory::class,
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'http-traffic-monitor' => [
                    'options' => [
                        'route'    => 'http:traffic:monitor [--log=] [--threshold-alert=]',
                        'defaults' => [
                            'controller' => Controller\HttpTrafficController::class,
                            'action'     => 'index',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
