<?php
/**
 * Date: 2/2/2020
 * Time: 8:00 PM
 */

namespace KemperAdmin\Model\Service;


use App\Di\InjectableInterface;
use KemperAdmin\Model\Repository\ConservationRepository;

class ConservationService implements InjectableInterface
{


    private $conservedPremium;
    private $conservedPaidPremium;
    private $disposedAndOpen;
    private $disposedAndClosed;
    private $totalDisposed;
    private $totalUnDisposed;
    private $disposersData;
    private $dispositionData;
    protected $di;
    /**
     * @var ConservationRepository
     * @Inject(name="KemperAdmin\Model\Repository\ConservationRepository")
     */
    protected $conservationRepo;

    /**
     * @var DisposersService
     * @Inject(name="KemperAdmin\Model\Service\DisposersService")
     */
    protected $disposersService;

    public function getConservationData($startDate, $endDate)
    {

        return [
            'conservedPaidPremium' => 123,
            'conservedPremium' => 23,
            'disposedClosed' => false,
            'disposedOpen' => true,
            'totalDisposed' => 12,
            'totalUnDisposed' => 35,
            'disposedClosedPercent' => 20,
            'disposedOpenPercent' => 0,
        ];

        $this->init($startDate, $endDate);
        $conservedPaidPremium = number_format($this->getConservedPaidPremium());
        $conservedPremium = number_format($this->getConservedPremium());
        $disposedClosed = $this->getDisposedAndClosed();
        $disposedOpen = $this->getDisposedAndOpen();
        $totalDisposed = $this->getTotalDisposed();
        $totalUnDisposed = $this->getTotalUnDisposed();
        if ($disposedClosed == 0 || $disposedOpen == 0) {
            $disposedClosedPercent = 0;
            $disposedOpenPercent = 0;
        } else {
            $disposedClosedPercent = ceil(($disposedClosed / ($disposedOpen + $disposedClosed)) * 100);
            $disposedOpenPercent = ceil(($disposedOpen / ($disposedOpen + $disposedClosed)) * 100);
        }

        return [
            'conservedPaidPremium' => $conservedPaidPremium,
            'conservedPremium' => $conservedPremium,
            'disposedClosed' => $disposedClosed,
            'disposedOpen' => $disposedOpen,
            'totalDisposed' => $totalDisposed,
            'totalUnDisposed' => $totalUnDisposed,
            'disposedClosedPercent' => $disposedClosedPercent,
            'disposedOpenPercent' => $disposedOpenPercent,
        ];
    }


    function createConnection()
    {
        $serverName = "rniokc81943\sqlexpress"; //serverName\instanceName
// Since UID and PWD are not specified in the $connectionInfo array,
// The connection will be attempted using Windows Authentication.
        $connectionInfo = array("Database" => "Data_Analytics");
        $conn = sqlsrv_connect($serverName, $connectionInfo);

        if ($conn) {
//        echo "Connection established.\r\n";
        } else {
            echo "Connection could not be established.<br />";
            die(print_r(sqlsrv_errors(), true));
        }
        return $conn;
    }

    function runQuery($Query)
    {
        $data = [];
        $conn = $this->createConnection();
        $result = sqlsrv_query($conn, $Query);
//    ECHO $Query."\r\n";
        $i = 0;
        if ($result) {
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                $data[$i] = $row;
                $i++;
            }
            sqlsrv_close($conn);

            return $data;
        }
        if (($errors = sqlsrv_errors()) != null) {
            ECHO $Query . "\r\n";
            foreach ($errors as $error) {
                echo "SQLSTATE: " . $error['SQLSTATE'] . "\r\n";
                echo "code: " . $error['code'] . "\r\n";
                echo "message: " . $error['message'] . "\r\n";
            }
        }
    }

    public function init($startDate, $endDate)
    {
        $filterClause = [];
        if ($startDate) {
            $filterClause['startingDate'] = $startDate;
        }
        if ($endDate) {
            $filterClause['endingDate'] = $endDate;
        }

        $this->initConservedPaidPremium($filterClause);
        $this->setConservedPremium($filterClause);
        $this->setDisposedAndClosed($filterClause);
        $this->setDisposedAndOpen($filterClause);
        $this->setTotalDisposed($filterClause);
        $this->setTotalUnDisposed($filterClause);
        $this->setDisposersData();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConservedPaidPremium()
    {
        return $this->conservedPaidPremium;
    }

    /**
     * @param mixed $filter
     */
    public function initConservedPaidPremium($filter)
    {
        $result = $this->conservationRepo->getConservedPaidPremium($filter);
        //Todo: Fetch the data from the result correctly
        //Do any modifications to data / decorate the data and return to controller
        $this->setConservedPaidPremium($result[0]['premium']);
    }

    /**
     * @param mixed $conservedPaidPremium
     * @return ConservationService
     */
    public function setConservedPaidPremium($conservedPaidPremium)
    {
        $this->conservedPaidPremium = $conservedPaidPremium;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getConservedPremium()
    {
        return $this->conservedPremium;
    }

    /**
     * @param mixed $filter
     */
    public function setConservedPremium($filter)
    {
        if ($filter) {
            $startingDate = $filter['startingDate'];
            $endingDate = $filter['endingDate'];
            $timeStart = strtotime(str_replace(",", "", $startingDate));
            $startingDate = date('Y-m-d', $timeStart);
            $timeEnd = strtotime(str_replace(",", "", $endingDate));
            $endingDate = date('Y-m-d', $timeEnd);
            $query = "select sum(premium) as premium from (
                    select distinct * from (
                    select policy_number as [policy number],
                    due_date as [due date],
                    monthly_premium as premium 
                    from Bill190 left join rmcashytdmst0 
                    on policy_number = YTD_POL 
                    and due_date = CONVERT(DATE,CONVERT(VARCHAR,LEFT(convert(varchar,YTD_DUEDT),4) + '-' + SUBSTRING(CAST(YTD_DUEDT AS VARCHAR),5,2)+ '-' + RIGHT(convert(varchar,YTD_DUEDT),2)))
                    where disposition_id in (14,5) and bill_date between '$startingDate' and '$endingDate')a
                  )b";
        } else {
            $query = "select sum(premium) as premium from (
                    select distinct * from (
                    select policy_number as [policy number],
                    due_date as [due date],
                    monthly_premium as premium 
                    from Bill190 left join rmcashytdmst0 
                    on policy_number = YTD_POL 
                    and due_date = CONVERT(DATE,CONVERT(VARCHAR,LEFT(convert(varchar,YTD_DUEDT),4) + '-' + SUBSTRING(CAST(YTD_DUEDT AS VARCHAR),5,2)+ '-' + RIGHT(convert(varchar,YTD_DUEDT),2)))
                    where disposition_id in (14,5))a
                  )b";
        }

        $result = $this->runQuery($query);
        $total = $this->conservedPaidPremium + $result[0]['premium'];
        $this->conservedPremium = $total;

    }

    /**
     * @return mixed
     */
    public function getDisposedAndOpen()
    {
        return $this->disposedAndOpen;
    }

    /**
     * @param mixed $filter
     */
    public function setDisposedAndOpen($filter)
    {
        if ($filter) {
            $startingDate = $filter['startingDate'];
            $endingDate = $filter['endingDate'];
            $timeStart = strtotime(str_replace(",", "", $startingDate));
            $startingDate = date('Y-m-d', $timeStart);
            $timeEnd = strtotime(str_replace(",", "", $endingDate));
            $endingDate = date('Y-m-d', $timeEnd);
            $query = "select count(*) as [disposed and still open] from Bill190 where disposition_id in (1,2,10,5) and disposer != 0 and bill_date between '$startingDate' and '$endingDate'";
        } else {
            $query = "select count(*) as [disposed and still open] from Bill190 where disposition_id in (1,2,10,5) and disposer != 0";
        }
        $result = $this->runQuery($query);
        $this->disposedAndOpen = $result[0]['disposed and still open'];
    }

    /**
     * @return mixed
     */
    public function getDisposedAndClosed()
    {
        return $this->disposedAndClosed;
    }

    /**
     * @param mixed $filter
     */
    public function setDisposedAndClosed($filter)
    {
        if ($filter) {
            $startingDate = $filter['startingDate'];
            $endingDate = $filter['endingDate'];
            $timeStart = strtotime(str_replace(",", "", $startingDate));
            $startingDate = date('Y-m-d', $timeStart);
            $timeEnd = strtotime(str_replace(",", "", $endingDate));
            $endingDate = date('Y-m-d', $timeEnd);
            $query = "select count(*) as [disposed and closed] from Bill190 where disposition_id not in (1,2,10,5) and disposer != 0 and bill_date between '$startingDate' and '$endingDate'";
        } else {
            $query = "select count(*) as [disposed and closed] from Bill190 where disposition_id not in (1,2,10,5) and disposer != 0 ";
        }
        $result = $this->runQuery($query);
        $this->disposedAndClosed = $result[0]['disposed and closed'];
    }


    public function getTotalDisposed()
    {
        return $this->totalDisposed;
    }


    public function setTotalDisposed($filter)
    {
        if ($filter) {
            $startingDate = $filter['startingDate'];
            $endingDate = $filter['endingDate'];
            $timeStart = strtotime(str_replace(",", "", $startingDate));
            $startingDate = date('Y-m-d', $timeStart);
            $timeEnd = strtotime(str_replace(",", "", $endingDate));
            $endingDate = date('Y-m-d', $timeEnd);
            $query = "select count(*) as [total disposed] from Bill190 where disposer != 0 and bill_date between '$startingDate' and '$endingDate'";
        } else {
            $query = "select count(*) as [total disposed] from Bill190 where disposer != 0";
        }
        $result = $this->runQuery($query);
        $this->totalDisposed = $result[0]['total disposed'];
    }

    /**
     * @return mixed
     */
    public function getTotalUnDisposed()
    {
        return $this->totalUnDisposed;
    }

    /**
     * @param mixed $filter
     */
    public function setTotalUnDisposed($filter)
    {
        if ($filter) {
            $startingDate = $filter['startingDate'];
            $endingDate = $filter['endingDate'];
            $timeStart = strtotime(str_replace(",", "", $startingDate));
            $startingDate = date('Y-m-d', $timeStart);
            $timeEnd = strtotime(str_replace(",", "", $endingDate));
            $endingDate = date('Y-m-d', $timeEnd);
            $query = "select count(*) as [total unDisposed] from Bill190 where disposer = 0 and status != 1 and bill_date between '$startingDate' and '$endingDate'";
        } else {
            $query = "select count(*) as [total unDisposed] from Bill190 where disposer = 0 and status != 1";
        }
        $result = $this->runQuery($query);
        $this->totalUnDisposed = $result[0]['total unDisposed'];
    }

    /**
     * @return mixed
     */
    public function getDisposersData()
    {
        return $this->disposersData;
    }

    public function setDisposersData()
    {
        $query = "select * from disposer";
        $result = $this->runQuery($query);
        $this->disposersData = $result;
    }

    /**
     * @return mixed
     */
    public function getDispositionData()
    {
        return $this->dispositionData;
    }

    /**
     * @param mixed $dispositionData
     */
    public function setDispositionData()
    {
        $query = "select * from disposer";
        $result = $this->runQuery($query);
        $this->dispositionData = $result;
    }


    public function setDi($di)
    {
        $this->di = $di;
        return $this;
    }

    /**
     * @param ConservationRepository $conservationRepo
     * @return ConservationService
     */
    public function setConservationRepo($conservationRepo)
    {
        $this->conservationRepo = $conservationRepo;
        return $this;
    }

    public function getDisposerData()
    {
        return [
            [
                'disposer_id' => '123',
                'disposer_description' => 'blah',
            ],
            [
                'disposer_id' => '124',
                'disposer_description' => 'blah2',
            ],
        ];
        return $this->disposersService->getDisposers();

    }

    /**
     * @param DisposersService $disposersService
     * @return ConservationService
     */
    public function setDisposersService($disposersService)
    {
        $this->disposersService = $disposersService;
        return $this;
    }
}