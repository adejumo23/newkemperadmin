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
    public function getTotalPremiumPerPerYearDisposer($disposerId)
    {
        $query =<<<SQL
  select
sum([YTD_PREM]) as premiumYearly
from Bill190 left join rmcashytdmst0 
on YTD_POL =  policy_number
and CONVERT(DATE,CONVERT(VARCHAR,LEFT(convert(varchar,YTD_DUEDT),4) + '-' + SUBSTRING(CAST(YTD_DUEDT AS VARCHAR),5,2)+ '-' + RIGHT(convert(varchar,YTD_DUEDT),2))) =  due_date
where disposer = ? and disposition_id in (14,1,2,5) group by year(Bill_date)
SQL;
        $result= $this->executeQuery($query, [$disposerId]);
        return $result;
    }
    public function getDisposerYears()
    {
        $query = <<<SQL
select year(bill_date) as [year] from bill190 group by year(bill_date)
SQL;
        return $this->executeQuery($query);
    }
}