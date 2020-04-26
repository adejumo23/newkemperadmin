<?php


namespace KemperAdmin\Model\Service;


use App\Di\InjectableInterface;
use KemperAdmin\Model\Repository\ProductionRepository;

class ProductionService implements InjectableInterface
{
    private $newSales;
    private $refunds;
    private $net;

    /**
     * @var ProductionRepository
     * @Inject(name="KemperAdmin\Model\Repository\ProductionRepository")
     */
    protected $productionRepo;

    public function getProductionData($startDate, $endDate)
    {
        $this->init($startDate, $endDate);
        $newSales = $this->getNewSales();
        $refunds = $this->getRefunds();
        $net = $this->getNet();
        return [
            'newSales' => $newSales,
            'refunds' => $refunds,
            'net' => $net,
        ];
    }
    public function init($startDate, $endDate)
    {
        $filterClause = [];
        if ($startDate) {
            $filterClause['startingDate'] = $startDate;
        }
        if ($endDate) {
            $filterClause['endingDate'] = $endDate;
        }
        $this->productionRepo->init($filterClause);
        $this->initNewSales();
        $this->initRefunds();
        $this->initNet();
        return $this;
    }

    public function getNewSales()
    {
        return $this->newSales;
    }
    public function setNewSales($newSales)
    {
        $this->newSales = $newSales;
        return $this;
    }
    public function initNewSales()
    {
        $result = $this->productionRepo->getProductionData();
        $this->setNewSales($result['New sales']);
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
    public function initRefunds()
    {
        $result = $this->productionRepo->getProductionData();
        $this->setRefunds($result['refunds']);
    }
    public function getNet()
    {
        return $this->refunds;
    }
    public function setNet($net)
    {
        $this->net = $net;
        return $this;
    }
    public function initNet()
    {
        $result = $this->productionRepo->getProductionData();
        $this->setNet($result['net']);
    }
}