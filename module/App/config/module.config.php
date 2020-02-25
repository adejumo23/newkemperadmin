<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace App;

use App\Di\Injector;
use App\Di\InjectorFactory;

return [
    'service_manager' => [
        'factories' => [
            'Zend\Session\Config\ConfigInterface' => 'Zend\Session\Service\SessionConfigFactory',
            'Zend\Session\Storage\StorageInterface' => 'Zend\Session\Service\SessionStorageFactory',
            'Zend\Session\ManagerInterface' => 'Zend\Session\Service\SessionManagerFactory',
//            Injector::class => InjectorFactory::class,
        ],
        'abstract_factories' => [
//            'Zend\Session\Service\ContainerAbstractFactory',
//            InjectorFactory::class,
        ],
    ],
    'session_config' => [
        'remember_me_seconds' => 1800,
        'name' => 'Kemper_Auth',
    ],
    'session_storage' => [
        'type' => 'SessionArrayStorage',
        'options' => [], // Likely don't want to seed it
    ],
    'session_containers' => [
        'User',
    ],
    'kemperdb' => [
        'driver' => 'sqlsrv',
        'hostname' => 'localhost\SQLEXPRESS',
        'username' => 'sa',
        'password' => 'tiger',
        'database' => 'kemperadmin',
    ],
];
