<?php


namespace KemperAdmin\Controller;

use App\AbstractAppController;
use Exception;
use KemperAdmin\Model\Service\ProductionService;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ProductionController extends AbstractAppController
{
    /**
     * @var ProductionService
     * @Inject(name="KemperAdmin\Model\Service\ProductionService")
     */
    protected $productionService;
    public function indexAction()
    {
        $identity = $this->getIdentity();
        $firstName = $identity->getFirstname();
        $lastName = $identity->getLastname();
        $startDate = $this->request->getQuery('startdate');
        $endDate = $this->request->getQuery('enddate');
        $data = $this->productionService->getProductionData($startDate, $endDate);

    }
}