<?php
/**
 * Date: 1/23/2020
 * Time: 9:37 PM
 */

namespace App;
use App\Auth\Identity;
use App\Model\Service\UserService;
use mysql_xdevapi\Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;


/**
 * @property Identity|null identity
 */
class AbstractAppController extends AbstractActionController
{

    /**
     * @var AuthenticationService
     */
    protected $authService;


    /**
     * Register the default events for this controller
     *
     * @return void
     */
    protected function attachDefaultListeners()
    {
        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'authenticate'], 999);
    }

    public function authenticate()
    {
        $userService = new UserService();
        $this->authService = $userService->getAuth();
        if (!$this->authService->hasIdentity()) {
            throw new \Exception("Invalid Session. Please login again.");
        }
        $this->identity = $this->authService->getIdentity();
    }

    protected function getIdentity()
    {
        if (!$this->identity) {
            return $this->plugin('identity');
        }
        return $this->identity;
    }

}