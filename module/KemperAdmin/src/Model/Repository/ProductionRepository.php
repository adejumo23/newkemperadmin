<?php


namespace KemperAdmin\Model\Repository;


use App\Model\Repository\AbstractRepository;

class ProductionRepository extends AbstractRepository
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
    public function getProductionData()
    {
        $query = <<<SQL
select sum (case when [sorc] not in ('JTRN','AP-P','AP-D','JMST') then [premium] else 0 end) as [New sales], 
	              sum (case when [sorc] in ('JTRN','AP-P','AP-D','JMST') then [premium] else 0 end) as [refunds],
	              Sum(Prod440anydetail.[premium]) AS [Net]
	              from prod440anydetail 
	              {$this->addDueDateCondition}
SQL;
        $result = $this->executeQuery($query, $this->params);
        return $result;
    }

}