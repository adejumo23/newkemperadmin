<?php
/**
 * Date: 2/24/2020
 * Time: 9:34 PM
 */

namespace KemperAdmin\Model\Service;


use KemperAdmin\Model\Repository\DisposerRepository;

class DisposerService
{
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


}