<?php


namespace KemperAdmin\Model\Service;


use App\Di\InjectableInterface;
use App\Helper\View\StringHelper;
use App\Model\Entity\User;
use App\Model\Service\UserAccessService;
use KemperAdmin\Model\Repository\DispositionRepository;
use KemperAdmin\Model\Repository\ProductionRepository;

class ProductionService implements InjectableInterface
{
    private $newSales;
    private $refunds;
    private $net;
    private $chartPremium;
    private $chartRefunds;
    private $chartSales;
    private $rvps;
    private $managers;

    /**
     * @var ProductionRepository
     * @Inject(name="KemperAdmin\Model\Repository\ProductionRepository")
     */
    protected $productionRepo;
    /**
     * @var \App\Auth\Identity
     */
    protected $identity;
    /**
     * @var UserAccessService
     * @Inject(name="App\Model\Service\UserAccessService")
     */
    protected $userAccessService;

    public function getProductionData($startDate, $endDate,$agent)
    {
//        return [];
        $this->init($startDate, $endDate,$agent);
        $newSales = $this->getNewSales();
        $refunds = $this->getRefunds();
        $net = $this->getNet();
        $chartPremium = $this->getChartPremium();
        $chartRefunds = $this->getChartRefunds();
        $chartSales = $this->getChartSales();
        $rvps = $this->getRvps();
        $managers = $this->getManagers();
        return [
            'newSales' => $newSales,
            'refunds' => $refunds,
            'net' => $net,
            'chart' =>['chartPremium' => $chartPremium,
                        'chartSales'=> $chartSales,
                        'chartRefunds'=>   $chartRefunds],
            'managers'=> $managers
        ];
    }
    public function init($startDate, $endDate,$agent)
    {
        $filterClause = [];
        if ($startDate) {
            $filterClause['startingDate'] = $startDate;
        }
        if ($endDate) {
            $filterClause['endingDate'] = $endDate;
        }
        if ($agent) {
            $filterClause['agent'] = $agent;
        }
        $this->productionRepo->init($filterClause);
        $this->initNewSales();
        $this->initRefunds();
        $this->initNet();
        $this->initProductionChartPremium();
        $this->initProductionChartRefunds();
        $this->initProductionChartSales();
        $this->initRvpsData();
//        $this->initManagersFromRvps($rvp);
        return $this;
    }
    public function initNewSales()
    {
        $result = $this->productionRepo->getProductionData();
        $this->setNewSales($result[0]['New sales']);
    }


    public function initRefunds()
    {
        $result = $this->productionRepo->getProductionData();
        $this->setRefunds($result[0]['refunds']);
    }
    public function getRefunds()
    {
        return $this->refunds;
    }
    public function setRefunds($refunds)
    {
        $this->refunds = $refunds;
        return $this;
    }

    public function initNet()
    {
        $result = $this->productionRepo->getProductionData();
        $this->setNet($result[0]['Net']);
    }
    public function getNet()
    {
        return $this->net;
    }
    public function setNet($net)
    {
        $this->net = $net;
        return $this;
    }

    public function initProductionChartPremium()
    {
        $resultPremiumChartData = $this->productionRepo->getPremiumChartData();
        $labels = array_column($resultPremiumChartData, 'MONTH');
        $premium = array_column($resultPremiumChartData, 'NET PREMIUM');
        $response = ['labels'=> $labels,
                     'premium'=> $premium
            ];
        $this->setChartPremium($response);
    }
    public function getChartPremium()
    {
        return $this->chartPremium;
    }
    public function setChartPremium($chartPremium)
    {
        $this->chartPremium = $chartPremium;
        return $this;
    }
    public function initProductionChartRefunds()
    {
        $resultRefundChartData = $this->productionRepo->getRefundChartData();
        $labels = array_column($resultRefundChartData, 'MONTH');
        $refund = array_column($resultRefundChartData, 'refunds');
        $response = ['labels'=> $labels,
            'refund'=> $refund
        ];
        $this->setChartRefunds($response);
    }
    public function getChartRefunds()
    {
        return $this->chartRefunds;
    }
    public function setChartRefunds($chartRefunds)
    {
        $this->chartRefunds = $chartRefunds;
        return $this;
    }
    public function initProductionChartSales()
    {
        $resultSalesChartData = $this->productionRepo->getSalesChartData();
        $labels = array_column($resultSalesChartData, 'MONTH');
        $sales = array_column($resultSalesChartData, 'sales');
        $response = ['labels'=> $labels,
            'sales'=> $sales
        ];
        $this->setChartSales($response);
    }
    public function getChartSales()
    {
        return $this->chartSales;
    }
    public function setChartSales($chartSales)
    {
        $this->chartSales = $chartSales;
        return $this;
    }
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getAgent()
    {
        return $this->agent;
    }
    public function initRvpsData()
    {
        $rvps = $this->productionRepo->getRvpData();
        return $rvps;
    }
    /**
     * @param ProductionRepository $productionRepo
     * @return ProductionService
     */
    public function setProductionRepo($productionRepo)
    {
        $this->productionRepo = $productionRepo;
        return $this;
    }
    public function getRvpsHierarchyData()
    {
        return [
            "1-03856" => "Roger Schuster",
            "1-03857" => "R Schuster",
            "1-03858" => "Ro Schuster",
            "1-03859" => "Rog Schuster",
        ];
    }

    public function getManagersHierarchyDataByRvp($rvpid)
    {
        return [
            "2-000B6" => "Jim Schuster",
            "2-000C7" => "Jerry Daniel",
            "2-000B7" => "Jim Schuster1",
            "2-000C8" => "Jerry Daniel1",
        ];
    }
    public function getAgentsHierarchyDataByManager($mgrId)
    {
        return [
            "3-000J4" => "Trent Hilderbrand",
            "3-000N7" => "Jason Dear",
            "3-000J5" => "Trent Hilderbrand1",
            "3-000N8" => "Jason Dear1",
        ];
    }
    /**
     * @return mixed
     */
    public function getRvps()
    {
        return $this->rvps;
    }

    /**
     * @param mixed $rvps
     * @return ProductionService
     */
    public function setRvps($rvps)
    {
        $this->rvps = $rvps;
        return $this;
    }
    public function initManagersFromRvps($rvp){
         $managerData = $this->productionRepo->getManagerData($rvp);
        return $managerData;
    }

    public function getAllowedUsersForUser($userId)
    {
        $allowedUsers = $this->userAccessService->getAllowedUsersForUser($userId);

        foreach ((array)$allowedUsers as $user) {
            $users[] = [
                'name' => $user['firstname'] . " " . $user['lastname'],
                'report' => $user['report'],
            ];
        }
        return $users;
    }

    /**
     * @return mixed
     */
    public function getManagers()
    {
        return $this->managers;
    }

    /**
     * @param mixed $managers
     * @return ProductionService
     */
    public function setManagers($managers)
    {
        $this->managers = $managers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewSales()
    {
        return $this->newSales;
    }

    /**
     * @param mixed $newSales
     * @return ProductionService
     */
    public function setNewSales($newSales)
    {
        $this->newSales = $newSales;
        return $this;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getFormattedDataSourceForHList($data)
    {
        $result = [];
        foreach ((array)$data as $record) {
            $result[$record['report']] =  StringHelper::titleCase($record['name']);
        }
        return $result;
    }

    /**
     * @param \App\Auth\Identity $identity
     * @return $this
     */
    public function setIdentity(\App\Auth\Identity $identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @return \App\Auth\Identity
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param UserAccessService $userAccessService
     * @return ProductionService
     */
    public function setUserAccessService($userAccessService)
    {
        $this->userAccessService = $userAccessService;
        return $this;
    }
}
