<?php

use Application\Controller\UserController;
use Application\Router\Route\Literal;
use Application\View\Helper\Url;
use Monolog\Handler\StreamHandler;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action' => 'login',
                    ]
                ]
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action' => 'logout',
                    ],
                ],
            ],
            'cabinet' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/cabinet',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action' => 'cabinet',
                    ],
                ],
            ],

        ]
    ],
    'view_manager' => [
        'template_map' => [
            'layout/layout' => APPLICATION_PATH . '/View/Templates/layout.phtml',
            'user/login' => APPLICATION_PATH . '/View/Templates/User/login.phtml',
            'user/cabinet' => APPLICATION_PATH . '/View/Templates/User/cabinet.phtml',
        ],
        'helpers' => [
            'url' => Url::class
        ],
    ],
    'db' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'dbname' => 'bit_test'
    ],
    'log' => [
        'auth' => [
            'writer' => [
                'name' => StreamHandler::class,
                'options' => [
                    'stream' => LOGS_PATH . '/auth.log'
                ]
            ]
        ],
        'money' => [
            'writer' => [
                'name' => StreamHandler::class,
                'options' => [
                    'stream' => LOGS_PATH . '/transfer.log'
                ]
            ]
        ],
    ]
];