<?php


namespace KemperAdmin\Model\Repository;


use App\Model\Repository\AbstractRepository;
use KemperAdmin\Model\Entity\ReportJob;

class ReportJobRepo extends AbstractRepository
{

    /**
     * @param string $username
     * @return ReportJob[]|null
     */
    public function findReportsForUser(string $username)
    {
        $query =<<<SQL
select * from report_jobs where (username = ? or shared = 1) order by timesubmitted desc;
SQL;
        $result = $this->executeQuery($query, [$username]);
        foreach ($result as $reportData) {
            $reportJobs[] = $this->hydrateEntity((new ReportJob()), $reportData);
        }
        return $reportJobs;
    }

    public function save($reportJobData)
    {
        $reportJob = new ReportJob();
        $reportJob->setFormData($reportJobData['formdata']);
        $reportJob->setUsername($reportJobData['username']);
        $reportJob->setReportTitle($reportJobData['report_title']);
        $reportid = parent::save($reportJob);
        $reportJob->setReportid($reportid);
        return $reportJob;
    }
}