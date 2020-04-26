<?php
/**
 * Date: 2/24/2020
 * Time: 9:34 PM
 */

namespace KemperAdmin\Model\Service;


use App\Di\InjectableInterface;
use KemperAdmin\Model\Repository\DisposerRepository;

class DisposerService implements InjectableInterface
{
    const CHART_LABELS = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    /**
     * @var DisposerRepository
     * @Inject(name="KemperAdmin\Model\Repository\DisposerRepository")
     */
    protected $disposersRepo;

    protected $disposers;
    protected $di;

    public function getDisposers()
    {
        if (!isset($this->disposers)) {
            $this->disposers = $this->disposersRepo->getDisposers();
        }
        return $this->disposers;
    }

    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @param DisposerRepository $disposersRepo
     * @return DisposerService
     */
    public function setDisposersRepo($disposersRepo)
    {
        $this->disposersRepo = $disposersRepo;
        return $this;
    }

    /**
     * @param int $disposerId
     * @return array
     */
    public function getDisposerDataById($disposerId = 1)
    {
        $resultPremium = $this->disposersRepo->getDisposerPremiumByDisposer($disposerId);
        $resultDispositions = $this->disposersRepo->getTotalDispositionsByDisposer($disposerId);
        $result = [];
        if ($resultPremium){
            $ids = array_column($resultPremium, 'premium');
            $outputPremium = implode(',', $ids);
            $outputPremium = explode(',',$outputPremium);
            $ids = array_column($resultDispositions, 'total dispositions');
            $outputDisposed = implode(',', $ids);
            $outputDisposed = explode(',',$outputDisposed);
            $result =[
                'chartData' => $outputPremium,
                'chartDisposed' => $outputDisposed,
                'chartLabels' => self::CHART_LABELS
            ];
        }
        return $result;
    }

    public function getDisposerPremiumPerYear()
    {
        $resultDisposerList = $this->disposersRepo->getDisposers();
        $resultDisposerYears = $this->disposersRepo->getDisposerYears();
        $ids = array_column($resultDisposerList, 'disposer_id');
        $outputDisposerList = implode(',', $ids);
        $outputDisposerList = explode(',',$outputDisposerList);
        $ids = array_column($resultDisposerList, 'disposer_description');
        $names = implode(',', $ids);
        $names = explode(',',$names);
        $ids = array_column($resultDisposerYears, 'year');
        $outputYears = implode(',', $ids);
        $outputYears = explode(',',$outputYears);
        $premiumResult = [];
        foreach($outputDisposerList as $data => $information ) {
            $returnedPremium = $this->disposersRepo->getTotalPremiumPerPerYearDisposer($information);
             $ids = array_column($returnedPremium, 'premiumYearly');
            $outputPremiumYearly = implode(',', $ids);
            $premiumResult[$information] = $outputPremiumYearly;
        }
        $resultData =[
            'chartData' => $premiumResult,
            'chartLabels' => $outputYears,
            'names' => $names
        ];
        return $resultData;
    }

}