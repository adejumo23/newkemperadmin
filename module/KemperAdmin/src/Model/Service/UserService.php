<?php


namespace KemperAdmin\Model\Service;


use App\Di\InjectableInterface;
use KemperAdmin\Model\Repository\UserRepository;

class UserService implements InjectableInterface
{
    private $userList;

    /**
     * @var UserRepository
     * @Inject(repo="App\Model\Entity\User")
     */
    protected $userRepo;

    /**
     * @param UserRepository $userRepo
     * @return UserService
     */

    public function setUserRepo($userRepo)
    {
        $this->userRepo = $userRepo;
        return $this;
    }
    public function getUsersInformationList(){
        $this->init();
        $this->getUserList();
    }
    public function init(){
        $this->iniUserList();
    }
    public function iniUserList(){
        $userList = $this->userRepo->getAllPossibleUsers();
        return $this->setUserList($userList);
    }
    /**
     * @return mixed
     */
    public function getUserList()
    {
        return $this->userList;
    }

    /**
     * @param mixed $userList
     * @return UserService
     */
    public function setUserList($userList)
    {
        $this->userList = $userList;
        return $this;
    }
    public function getUser($writingNumber){
        $userDetails = $this->userRepo->findOneBy([$writingNumber]);
        return $userDetails;
    }


}