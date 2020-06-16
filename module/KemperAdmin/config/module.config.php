<?php

namespace KemperAdmin;

use App\Di\AbstractFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\View\Strategy\JsonStrategy;

return [
    'router' => [
        'routes' => [
            'kemperadmin:home' => [
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
            'kemperadmin:login:logout' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/login/logout',
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'logout',
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
                    'route'    => '/reportcenter/index[/:report-title]',
                    'defaults' => [
                        'controller' => Controller\ReportController::class,
                        'action'     => 'index',
                        'report-title' => '',
                    ],
                ],
            ],
            'kemperadmin:report-center' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/reportcenter[/:classification]',
                    'defaults' => [
                        'controller' => Controller\ReportController::class,
                        'action'     => 'reportCenter',
                        'classification' => '',
                    ],
                ],
            ],
            'kemperadmin:report-service' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/reportService',
                    'defaults' => [
                        'controller' => Controller\ReportServiceController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'kemperadmin:generatereport' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/reportcenter/generate[/:report-title]',
                    'defaults' => [
                        'controller' => Controller\ReportController::class,
                        'action'     => 'generateReport',
                        'report-title' => '',
                    ],
                ],
            ],
            'kemperadmin:recentreports' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/reportcenter/recentreports',
                    'defaults' => [
                        'controller' => Controller\ReportController::class,
                        'action'     => 'recentReports',
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
            'kemperadmin:conservation:disposition' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/conservation/dispositionData',
                    'defaults' => [
                        'controller' => Controller\ConservationController::class,
                        'action'     => 'dispositionData',
                    ],
                ],
            ],
            'kemperadmin:conservation:productstats' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/conservation/productStatsData',
                    'defaults' => [
                        'controller' => Controller\ConservationController::class,
                        'action'     => 'productStatsData',
                    ],
                ],
            ],
            'kemperadmin:production' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/production',
                    'defaults' => [
                        'controller' => Controller\ProductionController::class,
                        'action'     => 'index',
                        'managerId' => '',
                    ],
                ],
            ],
            'kemperadmin:users' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/users',
                    'defaults' => [
                        'controller' => Controller\UsersController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'kemperadmin:users:edituser' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/users/:username',
                    'defaults' => [
                        'controller' => Controller\UsersController::class,
                        'action'     => 'edit',
                        'username' => '',
                    ],
                ],
            ],
            'kemperadmin:production:productiondata' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/production/productionData/:managerId',
                    'defaults' => [
                        'controller' => Controller\ProductionController::class,
                        'action'     => 'productionData',
                        'managerId' => '',
                    ],
                ],
            ],
            'kemperadmin:production:hierarchydata' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/production/hierarchyData',
                    'defaults' => [
                        'controller' => Controller\ProductionController::class,
                        'action'     => 'hierarchyData',
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
            Controller\ProductionController::class => InvokableFactory::class,
            Controller\UsersController::class => InvokableFactory::class,
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            AbstractFactory::class,
        ],
    ],
    'reportConfig' => include_once __DIR__ . '/reports.config.php',
    'view_manager' => [
//        'strategies' => [JsonStrategy::class],
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'kemper-admin/report/report-center' => __DIR__ . '/../view/kemper-admin/report/reports-list.phtml',
//            'kemper-admin/report/recent-reports' => __DIR__ . '/../view/kemperadmin/report/recent-reports.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
            __DIR__ . '/../view/kemper-admin',
        ],
    ],
];
