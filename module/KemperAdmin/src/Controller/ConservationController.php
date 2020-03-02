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
     * @return ViewModel
     * @throws Exception
     */
    public function indexAction()
    {

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
        /*
         * Return any view from any action
         */
        $viewModel = new ViewModel([
            'disposerArray' => $disposerArray,
            'disposerDataUrl' => $disposerDataUrl,  //Default DisposerDataUrl
            ]);
        $viewModel->setTemplate('kemper-admin/conservation/chartdatafilterdropdown');
        $data['chartdatafilterdropdown'] = $viewModel;

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
        $disposerService = $this->getContainer()->getInstance('KemperAdmin\Model\Service\DisposerService');
        $data = $disposerService->getDisposerDataById($disposerId);
        $data['status'] = count($data) > 0;
        echo json_encode($data);
        exit();
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


}