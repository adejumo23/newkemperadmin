<?php
/**
 * Date: 2/24/2020
 * Time: 8:22 PM
 */

namespace App\Di;


use App\Model\Entity\AbstractEntity;
use App\Model\Repository\AbstractRepository;
use Interop\Container\ContainerInterface;

class Injector
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Injector constructor.
     * @param ContainerInterface $container
     */
    public function __construct($container = null)
    {
        $this->container = $container;
    }

    /**
     * @param string $className
     * @return mixed
     * @throws \Exception
     */
    public function getInstance($className)
    {
        if (class_exists($className)) {
            $classObject = $this->container->get($className);
            $this->init($classObject);
            return $this->injectAnnotations($classObject);
        }
        throw  new \Exception('Class not found for injection: ' . $className);
    }

    private function init(&$classObject)
    {
        if ($classObject instanceof InitializableInterface) {
            $classObject->init();
        }
        if ($classObject instanceof ContainerAwareInterface) {
            $classObject->setContainer($this->container);
        }
    }

    /**
     * @param $classObject
     * @return mixed
     * @throws \ReflectionException
     */
    public function injectAnnotations(&$classObject)
    {
        $className = get_class($classObject);
        $reflectionClass = new \ReflectionClass($className);
        $classProperties = $reflectionClass->getProperties();
        foreach ($classProperties as $property) {
            $propertyComment = $property->getDocComment();
            if (strpos($propertyComment, '@Inject(name')) {
                $annotationArr = explode("\"", $propertyComment);
                $injectionClass = $annotationArr[1];
                if (class_exists($injectionClass)) {
                    $injectionClassInstance = $this->getInstance($injectionClass);
                }
                elseif ($this->container->has($injectionClass)) {
                    $injectionClassInstance = $this->container->get($injectionClass);
                }
                else {
                    throw  new \Exception('Class not found for property injection: ' . $className);
                }

                $propertyName = $property->getName();
                $setterMethod = 'set' . $propertyName;
                if (method_exists($classObject, $setterMethod)) {
                    $classObject->{$setterMethod}($injectionClassInstance);
                    continue;
                } else {
                    throw  new \Exception('Setter missing for class property injection: ' . $className . ':' . $setterMethod);
                }
            }
            if (strpos($propertyComment, '@Inject(repo')) {
                $annotationArr = explode("\"", $propertyComment);
                $entityClass = $annotationArr[1];
                if (class_exists($entityClass)) {
                    $entityClassInstance = $this->getInstance($entityClass);
                }
                elseif ($this->container->has($entityClass)) {
                    $entityClassInstance = $this->container->get($entityClass);
                }
                else {
                    throw  new \Exception('Entity Class not found for Repo injection: ' . $className);
                }
                /** @var AbstractEntity $entityClassInstance */
                $entityClassInstance->setMetadata();
                $repoClassName = $entityClassInstance->getRepositoryClass();
                if (class_exists($repoClassName)) {
                    $repoClassInstance = $this->getInstance($repoClassName);
                }
                elseif ($this->container->has($repoClassName)) {
                    $repoClassInstance = $this->container->get($repoClassName);
                }
                else {
                    throw  new \Exception('Repository Class not found/set in entity for Repo injection: ' . $className);
                }

                /** @var AbstractRepository $repoClassInstance */
                $repoClassInstance->setEntity($entityClassInstance);
                $injectionClassInstance = $repoClassInstance;
                $propertyName = $property->getName();
                $setterMethod = 'set' . $propertyName;
                if (method_exists($classObject, $setterMethod)) {
                    $classObject->{$setterMethod}($injectionClassInstance);
                    continue;
                } else {
                    throw  new \Exception('Setter missing for class property injection: ' . $className . ':' . $setterMethod);
                }
            }
        }
        $this->init($classObject);
        return $classObject;
    }
}