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
        if ($filter['startingDate']) {
            $startingDate = $filter['startingDate'];
            $endingDate = $filter['endingDate'];
            $timeStart = strtotime(str_replace(",", "", $startingDate));
            $startingDate = date('Y-m-d', $timeStart);
            $timeEnd = strtotime(str_replace(",", "", $endingDate));
            $endingDate = date('Y-m-d', $timeEnd);
            $this->addDueDateCondition = " where [sra date] between ? and ? ";
            $this->params = [$startingDate, $endingDate];
        } else {
            //Defaults
            $startingDate = '2020-01-01';
            $endingDate = '2020-12-31';
            $agent = '';
            $this->addDueDateCondition = " where [sra date] between ? and ? ";
            $this->params = [$startingDate, $endingDate];
        }
        if($filter['agent']){
            $agent = $filter['agent'];
            $this->addDueDateCondition .= " and [manager #] in (select distinct right(report,2) from hierarchy where report like '000%' and manager = ? union select right(?,2))";
            $this->params = [$startingDate, $endingDate,$agent,$agent];
        }
/*        if ($filter['agent']) {
            $this->addDueDateCondition .= " AND agent = ?";
            $this->params += $filter['agent'];
        }*/

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

    public function getPremiumChartData()
    {
        $query = <<<SQL
select Convert(char(3),cast([sra date] as datetime)) AS [MONTH], 
YEAR(cast([sra date] as datetime)) AS [YEAR], 
SUM ([Premium]) AS [NET PREMIUM] from prod440anydetail 
{$this->addDueDateCondition}
GROUP BY  Convert(char(3),
cast([sra date] as datetime)), 
YEAR(cast([sra date] as datetime)),
month(cast([sra date] as datetime)) 
order by YEAR(cast([sra date] as datetime)),month(cast([sra date] as datetime))
SQL;
        $result = $this->executeQuery($query, $this->params);
        return $result;
    }

    public function getRefundChartData()
    {
        $query = <<<SQL
select Convert(char(3),cast([sra date] as datetime)) AS [MONTH], 
YEAR(cast([sra date] as datetime)) AS [YEAR], 
sum ([premium]) as [refunds]
from prod440anydetail
{$this->addDueDateCondition} and [sorc] in ('JTRN','AP-P','AP-D','JMST') 
GROUP BY  Convert(char(3),
cast([sra date] as datetime)), 
YEAR(cast([sra date] as datetime)),
month(cast([sra date] as datetime)) 
order by YEAR(cast([sra date] as datetime)),month(cast([sra date] as datetime))
SQL;
        $result = $this->executeQuery($query, $this->params);
        return $result;
    }

    public function getSalesChartData()
    {
        $query = <<<SQL
select Convert(char(3),cast([sra date] as datetime)) AS [MONTH], 
YEAR(cast([sra date] as datetime)) AS [YEAR], 
sum ([premium]) as [sales]
from prod440anydetail 
{$this->addDueDateCondition} and [sorc] not in ('JTRN','AP-P','AP-D','JMST') 
GROUP BY  Convert(char(3),
cast([sra date] as datetime)), 
YEAR(cast([sra date] as datetime)),
month(cast([sra date] as datetime)) 
order by YEAR(cast([sra date] as datetime)),month(cast([sra date] as datetime))
SQL;
        $result = $this->executeQuery($query, $this->params);
        return $result;
    }
    public function getRvpData()
    {
        $query = <<<SQL
SELECT writingNumber as [report],[name] FROM Regional_Vice_Presidents
SQL;
        $result = $this->executeQuery($query, $this->params);
        return $result;
    }
    public function getManagerData($managerId)
    {
        $query = <<<SQL
select report,UserFirstName+' '+UserLastName as [name] from hierarchy inner join kams on writingNumber = report where manager = ?
SQL;
        $result = $this->executeQuery($query,[$managerId]);
        return $result;
    }
}