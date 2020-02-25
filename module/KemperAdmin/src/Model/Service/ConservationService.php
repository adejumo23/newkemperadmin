<?php
/**
 * Date: 2/2/2020
 * Time: 8:00 PM
 */

namespace KemperAdmin\Model\Service;


use App\Di\InjectableInterface;
use KemperAdmin\Model\Repository\ConservationRepository;
use phpDocumentor\Reflection\Types\Mixed_;

class ConservationService implements InjectableInterface
{


    private $conservedPremium;
    private $conservedPaidPremium;
    private $disposedAndOpen;
    private $disposedAndClosed;
    private $totalDisposed;
    private $totalUnDisposed;
    protected $di;
    /**
     * @var ConservationRepository
     * @Inject(name="KemperAdmin\Model\Repository\ConservationRepository")
     */
    protected $conservationRepo;

    /**
     * @var DisposerService
     * @Inject(name="KemperAdmin\Model\Service\DisposerService")
     */
    protected $disposerService;

    public function getConservationData($startDate, $endDate)
    {
        $this->init($startDate, $endDate);
        $conservedPaidPremium = $this->getConservedPaidPremium();
        $conservedPremium = $this->getConservedPremium();
        $disposedClosed = $this->getDisposedAndClosed();
        $disposedOpen = $this->getDisposedAndOpen();
        $totalDisposed = $this->getTotalDisposed();
        $totalUnDisposed = $this->getTotalUnDisposed();
        if ($disposedClosed == 0 || $disposedOpen == 0) {
            $disposedClosedPercent = 0;
            $disposedOpenPercent = 0;
        } else {
            $disposedClosedPercent = ceil(($disposedClosed / ($disposedOpen + $disposedClosed)) * 100);
            $disposedOpenPercent = ceil(($disposedOpen / ($disposedOpen + $disposedClosed)) * 100);
        }

        return [
            'conservedPaidPremium' => $conservedPaidPremium,
            'conservedPremium' => $conservedPremium,
            'disposedClosed' => $disposedClosed,
            'disposedOpen' => $disposedOpen,
            'totalDisposed' => $totalDisposed,
            'totalUnDisposed' => $totalUnDisposed,
            'disposedClosedPercent' => $disposedClosedPercent,
            'disposedOpenPercent' => $disposedOpenPercent,
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
        $this->conservationRepo->init($filterClause);

        $this->initConservedPaidPremium();
        $this->initConservedPremium();
        $this->initDisposedAndClosed();
        $this->initDisposedAndOpen();
        $this->initTotalDisposed();
        $this->initTotalUnDisposed();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConservedPaidPremium()
    {
        return $this->conservedPaidPremium;
    }
    /**
     */
    public function initConservedPaidPremium()
    {
        $result = $this->conservationRepo->getConservedPaidPremium();
        //Todo: Fetch the data from the result correctly
        //Do any modifications to data / decorate the data and return to controller
        $this->setConservedPaidPremium($result);
    }
    /**
     * @param mixed $conservedPaidPremium
     * @return ConservationService
     */
    public function setConservedPaidPremium($conservedPaidPremium)
    {
        $this->conservedPaidPremium = $conservedPaidPremium;
        return $this;
    }
    /**
     * @param mixed $conservedPremium
     * @return ConservationService
     */
    public function setConservedPremium($conservedPremium)
    {
        $this->conservedPremium = $conservedPremium;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getConservedPremium()
    {
        return $this->conservedPremium;
    }

    /**
     */
    public function initConservedPremium()
    {
        $result = $this->conservationRepo->getConservedPremium();
        $conservedPremium = $this->conservedPaidPremium + $result;
        $this->setConservedPremium($conservedPremium);
    }

    /**
     * @return mixed
     */
    public function getDisposedAndOpen()
    {
        return $this->disposedAndOpen;
    }

    /**
     * @param mixed $filter
     */
    public function initDisposedAndOpen()
    {
        $this->disposedAndOpen = $this->conservationRepo->getDisposedAndOpen();
    }

    /**
     * @return mixed
     */
    public function getDisposedAndClosed()
    {
        return $this->disposedAndClosed;
    }

    /**
     * @param $result
     */
    public function initDisposedAndClosed()
    {
        $this->disposedAndClosed = $this->conservationRepo->getDisposedAndClosed();
    }


    public function getTotalDisposed()
    {
        return $this->totalDisposed;
    }


    public function initTotalDisposed()
    {
        $this->totalDisposed = $this->conservationRepo->getTotalDisposed();
    }

    /**
     * @return mixed
     */
    public function getTotalUnDisposed()
    {
        return $this->totalUnDisposed;
    }

    /**
     */
    public function initTotalUnDisposed()
    {
        $this->totalUnDisposed = $this->conservationRepo->getTotalUnDisposed();
    }

    /**
     * @return mixed
     */
    public function getDisposerData()
    {
        return $this->disposerService->getDisposers();
    }

    public function setDi($di)
    {
        $this->di = $di;
        return $this;
    }

    /**
     * @param ConservationRepository $conservationRepo
     * @return ConservationService
     */
    public function setConservationRepo($conservationRepo)
    {
        $this->conservationRepo = $conservationRepo;
        return $this;
    }


    /**
     * @param DisposerService $disposerService
     * @return ConservationService
     */
    public function setDisposerService($disposerService)
    {
        $this->disposerService = $disposerService;
        return $this;
    }
}