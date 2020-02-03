<?php
/**
 * Date: 2/2/2020
 * Time: 7:37 PM
 */

namespace KemperAdmin\Controller;


use App\AbstractAppController;
use KemperAdmin\Model\Service\ConservationService;
use Zend\View\Model\ViewModel;

class ConservationController extends AbstractAppController
{

    /**
     * @return ViewModel
     * @throws \Exception
     */
    public function indexAction()
    {

        $startDate = $this->request->getQuery('startdate');
        $endDate = $this->request->getQuery('enddate');

        /** @var ConservationService $conservationService */
        $conservationService = $this->getDi()->getInstance('KemperAdmin\Model\Service\ConservationService');
        $data = $conservationService->getConservationData($startDate, $endDate);

        $disposerArray = $conservationService->getDisposerData();
        /*
         * Return any view from any action
         */
        $viewModel = new ViewModel(['disposerArray' => $disposerArray]);
        $viewModel->setTemplate('kemper-admin/conservation/chartdatafilterdropdown');
        $data['chartdatafilterdropdown'] = $viewModel;

        return new ViewModel($data);
    }

}