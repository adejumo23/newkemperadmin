<?php
/**
 * Date: 1/21/2020
 * Time: 10:26 PM
 */

namespace KemperAdmin\Controller;
use App\AbstractAppController;
use App\Model\Service\UserService;
use KemperAdmin\Model\Service\ReportService;
use Zend\View\Model\ViewModel;

use Zend\Db\Adapter;

class ReportController extends AbstractAppController
{


    public function indexAction()
    {
        $identity = $this->getIdentity();
        $username = $identity->getUsername();
        $reportService = new ReportService();
        $reportData = $reportService->calcReportDataForUser($username);

        return new ViewModel(['template' => 'kemperadmin/report/index'], ['data' => $reportData]);
    }
}