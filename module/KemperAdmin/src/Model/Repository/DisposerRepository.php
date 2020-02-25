<?php

namespace KemperAdmin\Model\Repository;


use App\Model\Repository\AbstractRepository;

class DisposerRepository extends AbstractRepository
{

    public function getDisposers()
    {
        $query = <<<SQL
select * from disposer
SQL;
        return $this->executeQuery($query);
    }

}