<?php


namespace KemperAdmin\Model\Repository;


use App\Model\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{

    public function getAllPossibleUsers()
    {
        $query = <<<SQL
select writing_number, userFirstName as firstName, userLastName as lastName from Kams where writing_Number like '000%' and endDate = '1900-01-01' 
SQL;
        $result = $this->executeQuery($query);
        return $result;
    }
    public function getSelectedUserDetails($writingNumber){
        $query = <<<SQL
select * from Kams where writing_Number = ? and endDate = '1900-01-01' 
SQL;
        $result = $this->executeQuery($query,[$writingNumber]);
        return $result;
    }
}
