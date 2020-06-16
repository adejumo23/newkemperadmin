<?php


namespace KemperAdmin\Di\Report\Permissions;


use App\Auth\Identity;

class Production implements PermissionsInterface
{

    /**
     * @param Identity $identity
     * @return mixed
     */
    public function isValid($identity)
    {
        return $identity->getPriv('production_reports') == 1;
    }
}