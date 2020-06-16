<?php


namespace KemperAdmin\Di\Report\Permissions;


use App\Auth\Identity;

class Conservation implements PermissionsInterface
{

    /**
     * @param Identity $identity
     * @return mixed
     */
    public function isValid($identity)
    {
        return $identity->getPriv('conservation_reports') == 1;
    }
}