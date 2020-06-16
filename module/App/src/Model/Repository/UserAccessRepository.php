<?php


namespace App\Model\Repository;


class UserAccessRepository extends AbstractRepository
{

    public function getAllowedUsers($userID)
    {
        $query = <<<SQL
select hierarchy.*, kams.UserFirstName as [firstname], kams.UserLastName as [lastname] from hierarchy join kams  on writingNumber = report where manager = ? 
SQL;
        $result = $this->executeQuery($query,[$userID]);
        return $result;
    }
}