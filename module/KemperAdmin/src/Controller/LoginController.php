<?php
/**
 * Date: 1/23/2020
 * Time: 10:44 PM
 */

namespace KemperAdmin\Controller;


use App\Model\Service\UserService;
use Zend\Mvc\Controller\AbstractActionController;

class LoginController extends AbstractActionController
{

    /**
     * @return void|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $user = 'raj';
        $pass = 'abc';
        $userService = new UserService();
        $result = $userService->authenticateUser($user, $pass);
//        print_r($result);
        $this->redirect()->toRoute('kemperadmin:api', []);
    }

}