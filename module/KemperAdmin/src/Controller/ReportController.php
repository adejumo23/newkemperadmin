<?php
/**
 * Date: 1/21/2020
 * Time: 10:26 PM
 */

namespace KemperAdmin\Controller;
use App\AbstractAppController;
use App\Di\InjectorFactory;
use App\Model\Service\UserService;
use KemperAdmin\Model\Service\ReportService;
use Zend\View\Model\ViewModel;
use Zend\Di\Di;

use Zend\Db\Adapter;

class ReportController extends AbstractAppController
{


    /**
     * @return ViewModel
     * @throws \Exception
     */
    public function indexAction()
    {
        $identity = $this->getIdentity();
        $username = $identity->getUsername();

        $injectorFactory = new InjectorFactory();
        $reportService = $injectorFactory->getInstance('KemperAdmin\Model\Service\ReportService');
//        $reportData = $reportService->calcReportDataForUser($username);

//        return new ViewModel(['template' => 'kemperadmin/report/index'], ['data' => $reportData]);
        return new ViewModel([]);
    }
}