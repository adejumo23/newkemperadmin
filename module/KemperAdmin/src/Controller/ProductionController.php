<?php


namespace KemperAdmin\Controller;

use App\AbstractAppController;
use Exception;
use KemperAdmin\Form\HierarchyForm;
use KemperAdmin\Model\Service\ProductionService;
use KemperAdmin\Template\HierarchyList;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class ProductionController extends AbstractAppController
{
    /**
     * @var ProductionService
     * @Inject(name="KemperAdmin\Model\Service\ProductionService")
     */
    protected $productionService;

    /**
     * @var HierarchyForm
     * @Inject(name="KemperAdmin\Form\HierarchyForm")
     */
    protected $hierarchyForm;

    /**
     * @var HierarchyList
     * @Inject(name="KemperAdmin\Template\HierarchyList")
     */
    protected $hierarchyListTemplate;


    public function indexAction()
    {
        $identity = $this->getIdentity();
        $firstName = $identity->getFirstname();
        $lastName = $identity->getLastname();
        $startDate = $this->request->getQuery('startdate');
        $endDate = $this->request->getQuery('enddate');
        $agent = $this->request->getQuery('agent');
        $this->productionService->setAgent($agent);
//        $sourceData = $this->productionService->getRvps();
        $this->hierarchyListTemplate->setHidden(true);
        $this->hierarchyListTemplate->setName('hierarchyForm');
        $this->hierarchyListTemplate->setUrl($this->url()->fromRoute('kemperadmin:production:hierarchydata'));
        $data = $this->productionService->getProductionData($startDate, $endDate);
        $sourceData = $this->productionService->initRvpsData();
//        $this->hierarchyListTemplate->setData($sourceData);
        $data['hierarchy'] = $this->hierarchyListTemplate;
        return new ViewModel($data);
    }

    public function hierarchyDataAction()
    {
        $startDate = $this->request->getQuery('startdate');
        $endDate = $this->request->getQuery('enddate');
        $personnelType = $this->request->getPost('personneltype');
        $dataAttrs = (array)$this->request->getPost();
        switch ($personnelType) {
            case 'manager':
                $rvpId = $this->request->getPost('listitemvalue');
                $data = $this->productionService->initManagersFromRvps($rvpId);
                $dataAttrs['personneltype'] = 'manager';
                break;
            default:
                $this->productionService->getProductionData($startDate, $endDate);
                $sourceData = $this->productionService->initRvpsData();
                $data = $sourceData;
                $dataAttrs['personneltype'] = 'manager';
        }
        $formattedData = $this->productionService->getFormattedDataSourceForHList($data);
        $this->hierarchyListTemplate->setDataAttributes($dataAttrs);
        $this->hierarchyListTemplate->setData($formattedData);
        $this->hierarchyListTemplate->setName('hierarchyForm');
        $html = $this->hierarchyListTemplate->getListHtml();
        $jsonData =  [
            'success' => true,
            'data' => $html,
        ];
        echo json_encode($jsonData);
        exit();
    }

    /**
     * @param ProductionService $productionService
     * @return ProductionController
     */
    public function setProductionService($productionService)
    {
        $this->productionService = $productionService;
        return $this;
    }

    /**
     * @param HierarchyForm $hierarchyForm
     * @return ProductionController
     */
    public function setHierarchyForm($hierarchyForm)
    {
        $this->hierarchyForm = $hierarchyForm;
        return $this;
    }

    /**
     * @param HierarchyList $hierarchyListTemplate
     * @return ProductionController
     */
    public function setHierarchyListTemplate($hierarchyListTemplate)
    {
        $this->hierarchyListTemplate = $hierarchyListTemplate;
        return $this;
    }
}