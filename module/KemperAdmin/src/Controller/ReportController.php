<?php
/**
 * Date: 1/21/2020
 * Time: 10:26 PM
 */

namespace KemperAdmin\Controller;

use App\AbstractAppController;
use App\Di\InjectorFactory;
use App\Form\Element\Form;
use App\Model\Service\UserService;
use KemperAdmin\Di\Report\ConfigFactory;
use KemperAdmin\Di\Report\FilterFactory;
use KemperAdmin\Form\ReportGenerateForm;
use KemperAdmin\Model\Entity\ReportJob;
use KemperAdmin\Model\Service\ReportService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Di\Di;

use Zend\Db\Adapter;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\View;

class ReportController extends AbstractAppController
{
    /**
     * @var ReportService
     * @Inject(name="KemperAdmin\Model\Service\ReportService")
     */
    protected $reportService;
    /**
     * @var FilterFactory
     * @Inject(name="KemperAdmin\Di\Report\FilterFactory")
     */
    protected $reportFilterFactory;
    /**
     * @var ConfigFactory
     * @Inject(name="KemperAdmin\Di\Report\ConfigFactory")
     */
    protected $reportConfigFactory;
    /**
     * @var ReportGenerateForm
     * @Inject(name="KemperAdmin\Form\ReportGenerateForm")
     */
    protected $reportGenerateForm;

    /**
     * @return ViewModel
     * @throws \Exception
     */
    public function indexAction()
    {
        $reportTitle = $this->getParam('report-title');
        if (empty($reportTitle)) {
            return $this->redirect()->toRoute('kemperadmin:home');
        }
        $reportConfig = $this->reportConfigFactory->getReportConfigByTitle($reportTitle);
        $classification = $reportConfig['report-title'];
        $reportFilters = $this->reportFilterFactory->getReportFiltersForReport($reportConfig['settings']['filters']);
        $reportForm = $this->reportGenerateForm;
        $reportForm->setName('reportgenerateform');
        $reportForm->setAction($this->url()->fromRoute('kemperadmin:generatereport', ['report-title' => $reportTitle]));
        $reportForm->setFilters($reportFilters);
        $reportTemplateModel = (new ViewModel([
            'reportForm' => $reportForm,
        ]))->setTemplate('kemper-admin/report/report-template');

        return new ViewModel([
            'classification' => $classification,
            'reportTemplate' => $reportTemplateModel
        ]);
    }

    public function reportCenterAction()
    {
        $classification = $this->getParam('classification');
        if (empty($classification)) {
            return $this->redirect()->toRoute('kemperadmin:home');
        }
        $reportConfigs = $this->reportConfigFactory->getReportsByClassification($classification);
        return (new ViewModel([
            'classification' => $classification,
            'reportConfigs' => $reportConfigs,
        ]));
    }

    /**
     * @return \Zend\Http\Response
     * @throws \Exception
     */
    public function generateReportAction()
    {
        $reportTitle = $this->getParam('report-title');
        if (empty($reportTitle)) {
            return $this->redirect()->toRoute('kemperadmin:home');
        }
        $formData = $this->params()->fromPost();
        $reportConfig = $this->reportConfigFactory->getReportConfigByTitle($reportTitle);

        $this->reportService->setRequestUrl($this->url()->fromRoute('kemperadmin:report-service'));


        $reportJobData = [
            'formdata' => $formData,
            'username' => $this->identity->getUsername(),
            'report_title' => $reportTitle
        ];

        $reportJob = $this->reportService->queueReport($reportJobData);
        $reportid = $reportJob->getReportid();
        $status = [
            'status' => $reportid ? true : false,
            'reportData' => $reportJob,
        ];
        echo json_encode($status);
        exit();
    }

    public function reportStatusAction()
    {

    }

    public function recentReportsAction()
    {
//        $reportConfigs = $this->reportConfigFactory->getReportsByClassification('production');
        $recentReports = $this->reportService->getRecentReports($this->getIdentity());
        $data = [
            'recentReports' => $recentReports
        ];
        /** @var PhpRenderer $viewRenderer */
        $phpRenderer = $this->container->getContainer()->get('ViewRenderer');
        $viewModel = (new ViewModel($data))->setTemplate('kemper-admin/report/recent-reports');
        $recentReportsHtml = $phpRenderer->render($viewModel);
        $response = [
            'status' => count($recentReports) > 0,
            'body' => $recentReportsHtml,
        ];
        ob_clean();
        echo json_encode($response);
        exit();
//        return (new JsonModel($response));
    }

    public function savedReportsAction()
    {

    }

    /**
     * @param ReportService $reportService
     * @return ReportController
     */
    public function setReportService($reportService)
    {
        $this->reportService = $reportService;
        return $this;
    }

    /**
     * @param ReportFilterFactory $reportFilterFactory
     * @return ReportController
     */
    public function setReportFilterFactory($reportFilterFactory)
    {
        $this->reportFilterFactory = $reportFilterFactory;
        return $this;
    }

    /**
     * @param ConfigFactory $reportConfigFactory
     * @return ReportController
     */
    public function setReportConfigFactory($reportConfigFactory)
    {
        $this->reportConfigFactory = $reportConfigFactory;
        return $this;
    }

    /**
     * @param ReportGenerateForm $reportGenerateForm
     * @return ReportController
     */
    public function setReportGenerateForm($reportGenerateForm)
    {
        $this->reportGenerateForm = $reportGenerateForm;
        return $this;
    }
}