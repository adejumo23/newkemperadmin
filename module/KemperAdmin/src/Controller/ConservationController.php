<?php
/**
 * Date: 2/2/2020
 * Time: 7:37 PM
 */

namespace KemperAdmin\Controller;


use App\AbstractAppController;
use Exception;
use KemperAdmin\Model\Service\ConservationService;
use KemperAdmin\Model\Service\DisposerService;
use KemperAdmin\Model\Service\DispositionService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ConservationController extends AbstractAppController
{

    /**
     * @var ConservationService
     * @Inject(name="KemperAdmin\Model\Service\ConservationService")
     */
    protected $conservationService;
    /**
     * @var disposerService
     * @Inject(name="KemperAdmin\Model\Service\DisposerService")
     */
    protected $disposerService;
    /**
     * @var DispositionService
     * @Inject(name="KemperAdmin\Model\Service\DispositionService")
     */
    protected $dispositionService;
    /**
     * @return ViewModel
     * @throws Exception
     */

    public function indexAction()
    {
        $identity = $this->getIdentity();
        $firstName = $identity->getFirstname();
        $lastName = $identity->getLastname();
        $startDate = $this->request->getQuery('startdate');
        $endDate = $this->request->getQuery('enddate');

        $data = $this->conservationService->getConservationData($startDate, $endDate);
        $disposerArray = $this->conservationService->getDisposerData();
        foreach ((array)$disposerArray as $key => $value) {
            $disposerArray[$key]['disposerDataUrl'] = $this->url()->fromRoute('kemperadmin:conservation:disposer', [
                'disposerId' => $value['disposer_id'],
            ]);
        }
        $disposerDataUrl = $this->url()->fromRoute('kemperadmin:conservation:disposer');
        $dispositionDataUrl = $this->url()->fromRoute('kemperadmin:conservation:disposition');
        $yearlyDataUrl = $this->url()->fromRoute('kemperadmin:conservation:yearly');
        /*
         * Return any view from any action
         */
        $viewModel = new ViewModel([
            'disposerArray' => $disposerArray,
            'disposerDataUrl' => $disposerDataUrl,  //Default DisposerDataUrl
            'dispositionDataUrl'=> $dispositionDataUrl,
            'yearlyDataUrl'=> $yearlyDataUrl
            ]);
        $viewModel->setTemplate('kemper-admin/conservation/chartdatafilterdropdown');
        $filterModel = new ViewModel(['filterDataDatesUrl' => 'kemperadmin:conservation']);
        $filterModel->setTemplate('kemper-admin/conservation/filterdropdowndashboard');

        $data['chartdatafilterdropdown'] = $viewModel;
        $data['filterdropdowndashboard'] = $filterModel;
        $data['firstName'] = $firstName;
        $data['lastName'] = $lastName;

        return new ViewModel($data);
    }

    /**
     * @return JsonModel
     * @throws Exception
     */
    public function disposerDataAction()
    {
        $disposerId = $this->params()->fromRoute('disposerId');
        /** @var DisposerService $disposerService */
//        $disposerService = $this->getContainer()->get('KemperAdmin\Model\Service\DisposerService');
        $disposerService = $this->disposerService;
        $data = $disposerService->getDisposerDataById($disposerId);
        $data['status'] = count($data) > 0;
        echo json_encode($data);
        exit();
    }
    public function dispositionDataAction()
    {
        $dispositionService = $this->dispositionService;
        $data = $dispositionService->getDispositionData();
        $data['status'] = count($data) > 0;
        echo json_encode($data);
        exit();
    }
    public function yearlyDataAction()
    {
        $disposerService = $this->disposerService;
        $data = $disposerService->getDisposerPremiumPerYear();
        $data['status'] = count($data) > 0;
        echo json_encode($data);
        exit();
    }
    /**
     * @param disposerService $disposerService
     * @return ConservationController
     */
    public function setDisposerService($disposerService)
    {
        $this->disposerService = $disposerService;
        return $this;
    }

    /**
     * @param ConservationService $conservationService
     * @return ConservationController
     */
    public function setConservationService($conservationService)
    {
        $this->conservationService = $conservationService;
        return $this;
    }
    /**
     * @param dispositionService $dispositionService
     * @return ConservationController
     */
    public function setDispositionService($dispositionService)
    {
        $this->dispositionService = $dispositionService;
        return $this;
    }

}