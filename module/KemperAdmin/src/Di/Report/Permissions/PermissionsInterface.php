<?php


namespace KemperAdmin\Di\Report\Permissions;


use App\Auth\Identity;
use App\Di\InjectableInterface;

interface PermissionsInterface extends InjectableInterface
{
    /**
     * @param Identity $identity
     * @return mixed
     */
    public function isValid($identity);

}