<?php
/**
 * Date: 2/2/2020
 * Time: 8:00 PM
 */

namespace KemperAdmin\Model\Service;


use App\Di\InjectableInterface;
use KemperAdmin\Model\Repository\ConservationRepository;

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

        return [
            'conservedPaidPremium' => 123,
            'conservedPremium' => 23,
            'disposedClosed' => false,
            'disposedOpen' => true,
            'totalDisposed' => 12,
            'totalUnDisposed' => 35,
            'disposedClosedPercent' => 20,
            'disposedOpenPercent' => 0,
        ];

        $this->init($startDate, $endDate);
        $conservedPaidPremium = number_format($this->getConservedPaidPremium());
        $conservedPremium = number_format($this->getConservedPremium());
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
        $this->setConservedPaidPremium($result[0]['premium']);
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
     * @return mixed
     */
    public function getConservedPremium()
    {
        return $this->conservedPremium;
    }

    /**
     * @param mixed $filter
     */
    public function initConservedPremium()
    {
        $result = $this->conservationRepo->getConservedPremium();
        $total = $this->conservedPaidPremium + $result[0]['premium'];
        $this->conservedPremium = $total;

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
        $result = $this->conservationRepo->getDisposedAndOpen();
        $this->disposedAndOpen = $result[0]['disposed and still open'];
    }

    /**
     * @return mixed
     */
    public function getDisposedAndClosed()
    {
        return $this->disposedAndClosed;
    }

    /**
     * @param mixed $filter
     */
    public function initDisposedAndClosed()
    {
        $result = $this->conservationRepo->getDisposedAndClosed();
        $this->disposedAndClosed = $result[0]['disposed and closed'];
    }


    public function getTotalDisposed()
    {
        return $this->totalDisposed;
    }


    public function initTotalDisposed()
    {
        $result = $this->conservationRepo->getTotalDisposed();
        $this->totalDisposed = $result[0]['total disposed'];
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
        $result = $this->conservationRepo->getTotalUnDisposed();
        $this->totalUnDisposed = $result[0]['total unDisposed'];
    }

    /**
     * @return mixed
     */
    public function getDisposerData()
    {
        return [
            [
                'disposer_id' => '123',
                'disposer_description' => 'blah',
            ],
            [
                'disposer_id' => '124',
                'disposer_description' => 'blah2',
            ],
        ];
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