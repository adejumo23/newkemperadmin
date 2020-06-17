<?php
/**
 * Date: 1/23/2020
 * Time: 10:31 PM
 */

namespace KemperAdmin\Model\Service;


use App\Auth\Identity;
use App\Di\InjectableInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use KemperAdmin\Di\Report\ConfigFactory;
use KemperAdmin\Di\Report\PermissionFactory;
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
    /**
     * @var ConfigFactory
     * @Inject(name="KemperAdmin\Di\Report\ConfigFactory")
     */
    protected $reportConfigFactory;

    /**
     * @var string
     */
    protected $requestUrl;
    /**
     * @var PermissionFactory
     * @Inject(name="KemperAdmin\Di\Report\PermissionFactory")
     */
    protected $reportPermissionsFactory;

    public function getReportStatus($transactionId)
    {
    }

    public function getAllReports($identity)
    {
        return $this->reportJobRepo->findBy(['username' => $identity->getUsername()]);
    }

    /**
     * @param Identity $identity
     * @return array
     * @throws \Exception
     */
    public function getRecentReports($identity)
    {
        $allowedReports = [];
        $recentReports = $this->reportJobRepo->findReportsForUser($identity->getUsername());
        //filter reports by permissions
        /** @var ReportJob $recentReport */
        foreach ((array)$recentReports as $recentReport) {
            $reportConfig = $this->reportConfigFactory->getReportConfigByTitle($recentReport->getReportTitle());
            $permissions = $reportConfig['permissions'];
            $isValid = true;
            foreach ((array)$permissions as $permission) {
                $permission = $this->reportPermissionsFactory->getPermission($permission);
                if (!$permission->isValid($identity)) {
                    $isValid = false;
                    break;
                }
            }
            if ($isValid) {
                $recentReport->setReportConfig($reportConfig);
                $allowedReports[] = $recentReport;
            }
        }
        return $allowedReports;
    }

    public function getSavedReports()
    {

    }

    /**
     * @param array $reportJobData
     * @return null|ReportJob
     * @throws \Exception
     */
    public function queueReport( $reportJobData)
    {
        $reportJob = $this->reportJobRepo->save($reportJobData);
//        $this->startReport($reportId);
        return $reportJob;
    }

    /**
     * @param int $reportId
     * @return ReportJob|array|null
     */
    public function getReportById($reportId)
    {
        return $this->reportJobRepo->findOneBy(['id' => $reportId]);
    }

    /**
     * @param $reportId
     * @return bool
     * @throws \Exception
     */
    public function startReport($reportId)
    {
        $requestUrl = $this->getRequestUrl();
        $cmd = 'php C:\devroot\webpages\newkemperadmin\app\index.php "'.$requestUrl.'" "{\"reportid\" : ' . $reportId . '}"';
        $out = "";
        exec($cmd, $out);

        return true;

//        $url = 'queue';
//        $authHeader = $this->getReportApiAuthHeader();
//        $request = new Request(
//            'post',
//            $url,
//            [
//                'Authentication' => $authHeader
//            ],
//            [
//                'reportId' => $reportId
//            ]
//        );
//        $gc = new Client([
//            // Base URI is used with relative requests
//            'base_uri' => 'http://localhost/newkemperadmin/app/index.php/reportService/',
//            // You can set any number of default request options.
//            'timeout'  => 5.0,
//        ]);
//        $response = $gc->sendAsync($request);
//        $status = $response->getHeader('status');
//        if ($status != 200) {
//            throw new \Exception('Queuing report failed');
//        }else{
//            return $response->getBody();
//        }
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

    public function setRequestUrl($url)
    {
        $this->requestUrl = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * @param PermissionFactory $reportPermissionsFactory
     * @return ReportService
     */
    public function setReportPermissionsFactory($reportPermissionsFactory)
    {
        $this->reportPermissionsFactory = $reportPermissionsFactory;
        return $this;
    }

    /**
     * @param ConfigFactory $reportConfigFactory
     * @return ReportService
     */
    public function setReportConfigFactory($reportConfigFactory)
    {
        $this->reportConfigFactory = $reportConfigFactory;
        return $this;
    }



}