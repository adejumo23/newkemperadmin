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

    public function getDisposerPremiumByDisposer($disposerId)
    {
        $query =<<<SQL
select sum(monthly_premium) premium from Bill190 where disposer = ? and year(bill_date) = year(getdate()) and disposition_id in (5,14) group by month(Bill_date)
SQL;
        $result= $this->executeQuery($query, [$disposerId]);
        return $result;
    }

    public function getTotalDispositionsByDisposer($disposerId)
    {
        $query =<<<SQL
        select count(disposer) as [total dispositions] from Bill190 where disposer = ?  and year(bill_date) = year(getdate()) and disposition_id != 0 group by month(Bill_date)
SQL;
        $result= $this->executeQuery($query, [$disposerId]);
        return $result;
    }

}