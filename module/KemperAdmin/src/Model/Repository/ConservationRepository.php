<?php
/**
 * Date: 2/2/2020
 * Time: 8:46 PM
 */

namespace KemperAdmin\Model\Repository;


use App\Model\Repository\AbstractRepository;

class ConservationRepository extends AbstractRepository
{


    public function getConservedPaidPremium($filter)
    {
        $params = [];
        $addDueDateCondition = '';
        if ($filter) {
            $startingDate = $filter['startingDate'];
            $endingDate = $filter['endingDate'];
            $timeStart = strtotime(str_replace(",", "", $startingDate));
            $startingDate = date('Y-m-d', $timeStart);
            $timeEnd = strtotime(str_replace(",", "", $endingDate));
            $endingDate = date('Y-m-d', $timeEnd);

            $addDueDateCondition = " and bill_date between ? and ? ";

            $params = [$startingDate, $endingDate];
        }
        $query = "select sum(b.ytd_premium) as premium from  (
                    select  distinct * from 
                    (
                        select [YTD_POL] as ytd_policy_number,
                        [YTD_DUEDT] as ytd_due_date,
                        [YTD_PREM] as ytd_premium,
                        [YTD_MODE] as ytd_mode 
                        from Bill190 left join rmcashytdmst0 
                        on policy_number = YTD_POL 
                        and due_date = CONVERT(DATE,CONVERT(VARCHAR,LEFT(convert(varchar,YTD_DUEDT),4) + '-' + SUBSTRING(CAST(YTD_DUEDT AS VARCHAR),5,2)+ '-' + RIGHT(convert(varchar,YTD_DUEDT),2)))
                        where [YTD_POL] is not null " .
            $addDueDateCondition .
            "
                        ) a group by 
                        ytd_policy_number,
                        ytd_due_date,
                        ytd_premium,
                        ytd_mode 
                    ) b ";

        return $this->executeQuery($query, $params);
    }

}