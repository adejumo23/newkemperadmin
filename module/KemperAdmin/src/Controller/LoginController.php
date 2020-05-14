<?php
/**
 * Date: 1/23/2020
 * Time: 10:44 PM
 */

namespace KemperAdmin\Controller;


use App\Model\Service\UserService;
use App\Request\RequestInterface;
use Zend\Http\Response;
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
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        if (!$this->request->isPost()) {
            if ($_GET['redirect_uri']) {
                return $this->redirect()->toUrl('../../login.php?redirect_uri=' . $_GET['redirect_uri']);
            }
            return $this->redirect()->toUrl('../../login.php');
        }
        $user = $this->request->getPost('username');
        $pass = $this->request->getPost('password');

        $result = $this->userService->authenticateUser($user, $pass);

        if ($result) {
            return new ViewModel($result);
        }
        if ($this->request->getQuery('redirect_uri')) {
            return $this->redirect()->toUrl($this->request->getQuery('redirect_uri'));
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