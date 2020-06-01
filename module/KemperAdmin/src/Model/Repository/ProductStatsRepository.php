<?php


namespace KemperAdmin\Model\Repository;
use App\Model\Repository\AbstractRepository;

class ProductStatsRepository extends AbstractRepository
{
    public function getProductStats()
    {
        $query = <<<SQL
select RMPOLCMST0.RMPOLC_FORM as product,sum([YTD_PREM]) as premium
						from Bill190 left join rmcashytdmst0 
                        on YTD_POL =  policy_number
                        and CONVERT(DATE,CONVERT(VARCHAR,LEFT(convert(varchar,YTD_DUEDT),4) + '-' + SUBSTRING(CAST(YTD_DUEDT AS VARCHAR),5,2)+ '-' + RIGHT(convert(varchar,YTD_DUEDT),2))) =  due_date
						left join RMPOLCMST0 on YTD_POL = RMPOLC_PN_KEY left join RMUTILMST0 on RMPOLCMST0.RMPOLC_FORM = UT_CODE
                        where disposer != 0 and disposition_id in (14,1,2,5) group by RMPOLCMST0.RMPOLC_FORM
SQL;
        return $this->executeQuery($query);
    }
    public function getDispositions()
    {
        $query = <<<SQL
select description from Dispositions order by description
SQL;
        return $this->executeQuery($query);
    }
}