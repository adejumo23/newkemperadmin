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
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    public function loginAction()
    {
        $user = $this->request->getPost('username');
        $pass = $this->request->getPost('password');

        $result = $this->userService->authenticateUser($user, $pass);

        if ($result) {
            return new ViewModel($result);
        }
        $this->redirect()->toRoute('kemperadmin:conservation', []);
//        $this->redirect()->toRoute('kemperadmin:api', []);
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