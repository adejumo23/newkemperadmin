<?php
/**
 * Date: 1/30/2020
 * Time: 10:40 PM
 */

namespace App\Di;



class InjectorFactory
{
    /**
     * @param string $className
     * @return mixed
     * @throws \Exception
     */
    public function getInstance($className)
    {
        if (class_exists($className)) {
            $classObject = new $className;
            $reflectionClass = new \ReflectionClass($className);
            $classProperties = $reflectionClass->getProperties();
            foreach ($classProperties as $property) {
                $propertyComment = $property->getDocComment();
                if (strpos($propertyComment, '@Inject')) {
                    $annotationArr = explode("\"", $propertyComment);
                    $injectionClass = $annotationArr[1];
                    if (class_exists($injectionClass)) {
                        $injectionClassInstance = $this->getInstance($injectionClass);
                        $propertyName = $property->getName();
                        $setterMethod = 'set' . $propertyName;
                        if (method_exists($classObject, $setterMethod)) {
                            $classObject->{$setterMethod}($injectionClassInstance);
                            continue;
                        }
                    }
                    throw  new \Exception('Class not found for property injection: ' . $className);
                }
            }
            $this->init($classObject);
            return $classObject;
        }
        throw  new \Exception('Class not found for injection: ' . $className);
    }

    private function init(&$classObject)
    {
        if ($classObject instanceof InjectableInterface) {
            $classObject->setDi($this);
        }
    }

}