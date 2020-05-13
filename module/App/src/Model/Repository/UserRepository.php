<?php
namespace App\Model\Repository;

class UserRepository extends AbstractRepository
{
    public function findUserPrivsByUsername($username)
    {
        $query = <<<SQL
SELECT 
conservation_dashboard_view,
conservation_listings,
production_dashboard_view,
production_reports,
conservation_reports
 FROM role_privs WHERE role_id=(select role_id from users where username = ?)
SQL;
        $privs = $this->executeQuery($query,[$username]);
        return reset($privs);
    }
}