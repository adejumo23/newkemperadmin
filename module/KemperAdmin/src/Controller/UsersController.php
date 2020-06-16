<?php


namespace KemperAdmin\Controller;


use App\AbstractAppController;
use App\Model\Entity\User;
use App\Model\Service\UserService;
use KemperAdmin\Form\EditUserForm;
use Zend\View\Model\ViewModel;

class UsersController extends AbstractAppController
{
    /**
     * @var UserService
     * @Inject(name="App\Model\Service\UserService")
     */
    protected $userService;
    /**
     * @var EditUserForm
     * @Inject(name="KemperAdmin\Form\EditUserForm")
     */
    protected $editUserForm;

    public function indexAction()
    {
        $username = $this->params('username');
        /** @var User[] $users */
        $users = $this->userService->findUsers();
        return new ViewModel(['users' => $users]);

    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws \Exception
     */
    public function editAction()
    {
        /** @var string $username */
        $username = $this->params('username');
        if ($username && $this->request->isPost()) {
            $user = $this->userService->findUserByUsername($username);
            $postData = $this->params()->fromPost();
            if (empty($postData['firstname'])) {
                throw new \Exception('Invalid Firstname');
            }if (empty($postData['lastname'])) {
                throw new \Exception('Invalid Lastname');
            }
            $this->userService->updateUser($username, $postData);
            return $this->redirect()->toRoute('kemperadmin:users');
        }
        if ($username) {
            $user = $this->userService->findUserByUsername($username);
            $this->editUserForm->setUser($user);
            $editUserView = new ViewModel(['form' => $this->editUserForm]);
            $editUserView->setTemplate('kemper-admin/users/edit-user');
            return $editUserView;
        }
        /** @var User[] $users */
        $users = $this->userService->findUsers();
        return new ViewModel(['users' => $users]);

    }




    /**
     * @param UserService $userService
     * @return UsersController
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
        return $this;
    }

    /**
     * @param EditUserForm $editUserForm
     * @return UsersController
     */
    public function setEditUserForm($editUserForm)
    {
        $this->editUserForm = $editUserForm;
        return $this;
    }

}