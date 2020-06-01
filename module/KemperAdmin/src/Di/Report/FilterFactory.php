<?php

namespace KemperAdmin\Di\Report;

use App\Di\ContainerAwareInterface;
use App\Di\InjectableInterface;
use Interop\Container\ContainerInterface;
use KemperAdmin\Form\Filter\AbstractFilter;
use KemperAdmin\Form\Filter\DateRange;

class FilterFactory implements ContainerAwareInterface, InjectableInterface
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    protected static $classMap = [
        'date-range' => DateRange::class,

    ];


    public function getReportFiltersForReport($filters)
    {
        $filterObjects = [];
        foreach ($filters as $filter) {
            if (!isset(self::$classMap[$filter['type']])) {
                throw new \Exception('Filter not found. Type: ' . $filter['type']);
            }
            /** @var AbstractFilter $filterObject */
            $filterObject = $this->container->get(self::$classMap[$filter['type']]);
            $filterObject->setSettings($filter['settings']??[]);
            $filterObject->setName($filter['name']??'');
            $filterObject->setDescription($filter['description']??'');
            $filterObject->setDefaults($filter['defaults']??[]);
            $filterObjects[] = $filterObject;
        }
        return $filterObjects;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

}