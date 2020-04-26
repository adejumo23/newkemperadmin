<?php
/**
 * Date: 1/23/2020
 * Time: 10:44 PM
 */

namespace KemperAdmin\Controller;


use App\Model\Service\UserService;
use App\Request\RequestInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LoginController extends AbstractActionController
{

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var UserService
     * @Inject(name="App\Model\Service\UserService")
     */
    protected $userService;

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function indexAction()
    {
//        return new ViewModel(['redirect' => $this->request->getQuery('redirect')]);
        if (!$this->request->isPost()) {
            return new ViewModel(['redirect' => $this->request->getQuery('redirect')]);
        }
        $user = $this->request->getPost('username');
        $pass = $this->request->getPost('password');

        $result = $this->userService->authenticateUser($user, $pass);

        if ($result) {
            return new ViewModel($result);
        }
        if ($this->request->getQuery('redirect')) {
            return $this->redirect()->toUrl($this->request->getQuery('redirect'));
        }
        return $this->redirect()->toRoute('kemperadmin:home', []);
    }

    public function logoutAction()
    {
        //I think that is it lol
        //user -> user info
        //role -> permissions
        //user * role = user permission map for the session //should be destroyed on logout and recreated when logging in
        $this->userService->clearCurrentSession();
        return $this->redirect()->toRoute('kemperadmin:login', []);
    }

    /**
     * @param UserService $userService
     * @return LoginController
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
        return $this;
    }

}