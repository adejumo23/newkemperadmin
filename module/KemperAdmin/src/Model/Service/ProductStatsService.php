<?php


namespace KemperAdmin\Model\Service;


use App\Di\InjectableInterface;
use KemperAdmin\Model\Repository\ProductStatsRepository;

class ProductStatsService implements InjectableInterface
{
    /**
     * @var ProductStatsRepository
     * @Inject(name="KemperAdmin\Model\Repository\ProductStatsRepository")
     */
    protected $productStatsRepo;
    protected $products;
    protected $di;


    public function setDi($di)
    {
        $this->di = $di;
    }
    /**
     * @param ProductStatsRepository $productStatsRepo
     * @return ProductStatsService
     */
    public function setProductStatsRepo($productStatsRepo)
    {
        $this->productStatsRepo = $productStatsRepo;
        return $this;
    }
    /**
     * @return array
     */
    public function getProductStatsData()
    {
        $resultStats = $this->products = $this->productStatsRepo->getProductStats();
        $result = [];
        if ($resultStats){
            $ids = array_column($resultStats, 'premium');
            $dataset = implode(',', $ids);
            $dataset = explode(',',$dataset);
            $ids = array_column($resultStats, 'product');
            $labels = implode(',', $ids);
            $labels = explode(',',$labels);
            $result =[
                'dataset' => $dataset,
                'labels' => $labels
            ];
        }
        return $result;
    }
}