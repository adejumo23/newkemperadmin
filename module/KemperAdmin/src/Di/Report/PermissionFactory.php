<?php

namespace KemperAdmin\Di\Report;

use App\Di\ContainerAwareInterface;
use App\Di\InjectableInterface;
use Interop\Container\ContainerInterface;
use KemperAdmin\Di\Report\Permissions\Conservation;
use KemperAdmin\Di\Report\Permissions\PermissionsInterface;
use KemperAdmin\Di\Report\Permissions\Production;
use KemperAdmin\Form\Filter\AbstractFilter;
use KemperAdmin\Form\Filter\DateRange;

class PermissionFactory implements ContainerAwareInterface, InjectableInterface
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    protected static $classMap = [
        'production' => Production::class,
        'conservation' => Conservation::class,
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

    /**
     * @param $permission
     * @return PermissionsInterface
     * @throws \Exception
     */
    public function getPermission($permission)
    {
        if (!isset(self::$classMap[$permission])) {
            throw new \Exception('Permission not found: '. $permission);
        }
        $permissionClass = self::$classMap[$permission];
        $permissionObj = $this->container->get($permissionClass);
        return $permissionObj;
    }

}