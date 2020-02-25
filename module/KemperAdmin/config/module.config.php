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
            'kemperadmin:login' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/login[/:action]',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'kemperadmin:api' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/api',
                    'defaults' => [
                        'controller' => Controller\ApiController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'kemperadmin:reports' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/reports[/:action]',
                    'defaults' => [
                        'controller' => Controller\ReportController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'kemperadmin:conservation' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/conservation',
                    'defaults' => [
                        'controller' => Controller\ConservationController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'kemperadmin:conservation:disposer' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/conservation/disposerData/:disposerId',
                    'defaults' => [
                        'controller' => Controller\ConservationController::class,
                        'action'     => 'disposerData',
                        'disposerId' => 1,
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\LoginController::class => InvokableFactory::class,
            Controller\ReportController::class => InvokableFactory::class,
            Controller\ApiController::class => InvokableFactory::class,
            Controller\ConservationController::class => InvokableFactory::class,
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
            'kemper-admin/login/index' => __DIR__ . '/../view/kemperadmin/login/index.phtml',
            'kemper-admin/login/login' => __DIR__ . '/../view/kemperadmin/login/login.phtml',
            'kemper-admin/report/index' => __DIR__ . '/../view/kemperadmin/report/index.phtml',
            'kemper-admin/conservation/index' => __DIR__ . '/../view/kemperadmin/conservation/index.phtml',
            'kemper-admin/conservation/chartdatafilterdropdown' => __DIR__ . '/../view/kemperadmin/conservation/chartDataFilterDropdown.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
