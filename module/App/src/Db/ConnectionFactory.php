<?php

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\Config\ConfigInterface;
use Zend\Session\Config\SessionConfig;

class ConnectionFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $services
     * @param null $canonicalName
     * @param string $requestedName
     * @return mixed
     * @throws \Interop\Container\Exception\ContainerException
     */
    public function createService(ServiceLocatorInterface $services,
                                  $canonicalName = null,
                                  $requestedName = ConfigInterface::class)
    {
        return $this($services, $requestedName);
    }

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

        $class  = SessionConfig::class;
        $config = $config['session_config'];


    }
}