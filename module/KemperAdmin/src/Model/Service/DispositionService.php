<?php


namespace KemperAdmin\Model\Service;

use App\Di\InjectableInterface;
use KemperAdmin\Model\Repository\DispositionRepository;

class DispositionService implements InjectableInterface
{
    /**
     * @var DispositionRepository
     * @Inject(name="KemperAdmin\Model\Repository\DispositionRepository")
     */
    protected $dispositionRepo;
    protected $dispositions;
    protected $di;

    public function getDispositions()
    {
        if (!isset($this->dispositions)) {
            $this->dispositions = $this->dispositionRepo->getDispositions();
        }
        return $this->dispositions;
    }

    public function setDi($di)
    {
        $this->di = $di;
    }
    /**
     * @param DispositionRepository $dispositionRepo
     * @return DispositionService
     */
    public function setDispositionRepo($dispositionRepo)
    {
        $this->dispositionRepo = $dispositionRepo;
        return $this;
    }
    /**
     * @return array
     */
    public function getDispositionData()
    {
        $resultStatsPerDispositions = $this->dispositionRepo->getDispositionStats();
        $result = [];
        if ($resultStatsPerDispositions){
            $ids = array_column($resultStatsPerDispositions, 'disposition count');
            $dataset = implode(',', $ids);
            $dataset = explode(',',$dataset);
            $ids = array_column($resultStatsPerDispositions, 'description');
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