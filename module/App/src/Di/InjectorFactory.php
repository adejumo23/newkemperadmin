<?php
/**
 * Date: 1/30/2020
 * Time: 10:40 PM
 */

namespace App\Di;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class InjectorFactory implements FactoryInterface
{
    protected $serviceManager;
    protected $request;
    protected $response;


    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $injector = new Injector($container);
        return $injector;
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
        if ($requestedName == 'injector') {
            return true;
        }
        return false;
    }


}