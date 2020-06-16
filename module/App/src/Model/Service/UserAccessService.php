<?php


namespace App\Model\Service;


use App\Di\InjectableInterface;
use App\Model\Repository\UserAccessRepository;

class UserAccessService implements InjectableInterface
{

    /**
     * @var UserAccessRepository
     * @Inject(name="App\Model\Repository\UserAccessRepository")
     */
    protected $userAccessRepo;

    public function getAllowedUsersForUser($userId)
    {
        return $this->userAccessRepo->getAllowedUsers($userId);
    }

    /**
     * @param UserAccessRepository $userAccessRepo
     * @return UserAccessService
     */
    public function setUserAccessRepo($userAccessRepo)
    {
        $this->userAccessRepo = $userAccessRepo;
        return $this;
    }
}