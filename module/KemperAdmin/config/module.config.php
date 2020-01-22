<?php

namespace KemperAdmin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'kemperadminhome' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'kemperadmin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/kemperadmin[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'kemperadmin:reports:literal' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/kemperadmin/reports[/:action]',
                    'defaults' => [
                        'controller' => Controller\ReportController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'kemperadmin:reports' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/kemperadmin/reports[/:action]',
                    'defaults' => [
                        'controller' => Controller\ReportController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\ReportController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'kemper-admin/index/index' => __DIR__ . '/../view/kemperadmin/index/index.phtml',
            'kemper-admin/report/index' => __DIR__ . '/../view/kemperadmin/report/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
