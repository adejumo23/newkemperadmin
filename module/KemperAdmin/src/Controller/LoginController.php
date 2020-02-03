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

        $userService = new UserService();
        $result = $userService->authenticateUser($user, $pass);

        if ($result) {
            return new ViewModel($result);
        }
        $this->redirect()->toRoute('kemperadmin:conservation', []);
    }

}