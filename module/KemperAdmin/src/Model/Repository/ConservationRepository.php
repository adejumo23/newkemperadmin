<?php
/**
 * Date: 2/2/2020
 * Time: 8:46 PM
 */

namespace KemperAdmin\Model\Repository;


use App\Model\Repository\AbstractRepository;
use Zend\Db\Adapter\Driver\ResultInterface;

class ConservationRepository extends AbstractRepository
{
    /**
     * @var string
     */
    protected $addDueDateCondition;
    /**
     * @var array
     */
    protected $params;

    /**
     * @param $filter
     */
    public function init($filter)
    {
        $this->params = [];
        $this->addDueDateCondition = '';
        if ($filter) {
            $startingDate = $filter['startingDate'];
            $endingDate = $filter['endingDate'];
            $timeStart = strtotime(str_replace(",", "", $startingDate));
            $startingDate = date('Y-m-d', $timeStart);
            $timeEnd = strtotime(str_replace(",", "", $endingDate));
            $endingDate = date('Y-m-d', $timeEnd);

            $this->addDueDateCondition = " and bill_date between ? and ? ";
            $this->params = [$startingDate, $endingDate];
        }else{
            //Defaults
            $startingDate = '2020-01-01';
            $endingDate = '2020-12-31';
            $this->addDueDateCondition = " and bill_date between ? and ? ";
            $this->params = [$startingDate, $endingDate];
        }

    }
    public function getConservedPaidPremium()
    {
        $query = <<<SQL
 select
                    sum([YTD_PREM]) as premium
                    from Bill190 left join rmcashytdmst0 
                  on YTD_POL =  policy_number
                  and CONVERT(DATE,CONVERT(VARCHAR,LEFT(convert(varchar,YTD_DUEDT),4) + '-' + SUBSTRING(CAST(YTD_DUEDT AS VARCHAR),5,2)+ '-' + RIGHT(convert(varchar,YTD_DUEDT),2))) =  due_date
                  where disposer != 0 and disposition_id in (14,1,2,5)
            {$this->addDueDateCondition}
SQL;
        $result = $this->executeQuery($query, $this->params);
        return $result[0]['premium'];
    }


    /**
     * @return ResultInterface|null
     */
    public function getConservedPremium()
    {
        $query = <<<SQL
select sum(premium) as premium from (
                    select distinct * from (
                    select policy_number as [policy number],
                    due_date as [due date],
                    monthly_premium as premium 
                    from bill190 left join rmcashytdmst0 
                    on policy_number = YTD_POL 
                    and due_date = CONVERT(DATE,CONVERT(VARCHAR,LEFT(convert(varchar,YTD_DUEDT),4) + '-' + SUBSTRING(CAST(YTD_DUEDT AS VARCHAR),5,2)+ '-' + RIGHT(convert(varchar,YTD_DUEDT),2)))
                    where disposition_id in (14,5)
            {$this->addDueDateCondition}
            )a
      )b
SQL;
        $result = $this->executeQuery($query, $this->params);
        return $result[0]['premium'];
    }

    public function getDisposedAndClosed()
    {
        $query =<<<SQL
select count(*) as [disposed_and_closed] from Bill190 where disposition_id not in (1,2,10,5) and disposer != 0 {$this->addDueDateCondition} 
SQL;
        $result = $this->executeQuery($query, $this->params);
        return $result[0]['disposed_and_closed'];
    }

    public function getDisposedAndOpen()
    {
        $query = <<<SQL
        select count(*) as [disposed_and_still_open] from Bill190 where disposition_id in (1,2,10,5) and disposer != 0 {$this->addDueDateCondition}
SQL;
        $result = $this->executeQuery($query, $this->params);
        return $result[0]['disposed_and_still_open'];
    }


    public function getTotalDisposed()
    {
        $query = <<<SQL
        select count(*) as [total_disposed] from Bill190 where disposer != 0  {$this->addDueDateCondition}
SQL;
        $result = $this->executeQuery($query, $this->params);
        return $result[0]['total_disposed'];
    }

    public function getTotalUnDisposed()
    {
        $query = <<<SQL
        select count(*) as [total_undisposed] from Bill190 where disposer = 0 and status != 1  {$this->addDueDateCondition}
SQL;
        $result = $this->executeQuery($query, $this->params);
        return $result[0]['total_undisposed'];
    }

}