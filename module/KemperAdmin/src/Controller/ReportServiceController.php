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
use GuzzleHttp\Promise\TaskQueue;
use KemperAdmin\Di\Report\ConfigFactory;
use KemperAdmin\Di\Report\FilterFactory;
use KemperAdmin\Form\ReportGenerateForm;
use KemperAdmin\Model\Entity\ReportJob;
use KemperAdmin\Model\Service\ReportService;
use Zend\Config\Processor\Queue;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Stdlib\SplQueue;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Di\Di;

use Zend\Db\Adapter;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\View;

class ReportServiceController extends AbstractAppController
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
     * @return Response
     */
    public function indexAction()
    {
        $postData = $this->params()->fromPost();
        /** @var Response $response */
        $response = $this->event->getResponse();
        $response->setStatusCode(200);
        $response->setContent([]);
        return $response;
    }

    public function queueAction()
    {
        $postData = $this->params()->fromPost();

        $reportId = $postData['reportId'];

        $this->runReport();
        $this->updateStatus();
        /** @var Response $response */
        $response = $this->event->getResponse();
        $response->setStatusCode(200);
        $response->setContent([
            'success' => true,
        ]);
        return $response;
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