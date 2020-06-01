<?php
/**
 * Date: 1/23/2020
 * Time: 10:31 PM
 */

namespace KemperAdmin\Model\Service;


use App\Di\InjectableInterface;
use KemperAdmin\Model\Entity\ReportJob;
use KemperAdmin\Model\Repository\ReportJobRepo;
use StdClass;

class ReportService implements InjectableInterface
{

    /**
     * @var ReportJobRepo
     * @Inject(repo="KemperAdmin\Model\Entity\ReportJob")
     */
    protected $reportJobRepo;

    public function getReportStatus($transactionId)
    {
    }

    public function getRecentReports()
    {
        return [];
    }

    public function getSavedReports()
    {

    }

    /**
     * @param ReportJob $reportJob
     */
    public function queueReport( $reportJob)
    {
        return $this->reportJobRepo->save($reportJob);
    }

    /**
     * @param ReportJobRepo $reportJobRepo
     * @return ReportService
     */
    public function setReportJobRepo($reportJobRepo)
    {
        $this->reportJobRepo = $reportJobRepo;
        return $this;
    }

}