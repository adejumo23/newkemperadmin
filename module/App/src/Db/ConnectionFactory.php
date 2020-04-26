<?php

namespace App\Db;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\Db\Adapter\Adapter as DbAdapter;

class ConnectionFactory implements AbstractFactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws \Interop\Container\Exception\ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        if (! isset($config['kemperdb']) || ! is_array($config['kemperdb'])) {
            throw new ServiceNotCreatedException(
                'Configuration is missing a "kemperdb" key, or the value of that key is not an array'
            );
        }
        $dbConfig = $config['kemperdb'];

        //Todo: Read config using Config Reader
        return new Connection(new DbAdapter($dbConfig));

    }

    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (! $container->has('config') || ! array_key_exists(self::class, $container->get('config'))) {
            return false;
        }
        $config = $container->get('config');
        $dependencies = $config['kemperdb'];
        return is_array($dependencies);
    }
}