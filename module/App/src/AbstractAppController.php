<?php
/**
 * Date: 1/23/2020
 * Time: 9:37 PM
 */

namespace App;
use App\Auth\Identity;
use App\Di\Injector;
use App\Di\InjectorFactory;
use App\Model\Service\UserService;
use mysql_xdevapi\Exception;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use App\Request\RequestInterface as Request;

/**
 * @property Identity|null identity
 */
class AbstractAppController extends AbstractActionController implements Di\InjectableInterface
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var Injector
     */
    protected $di;

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
            return $this->redirect()->toRoute('kemperadmin:login');
//            throw new \Exception("Invalid Session. Please login again.");
        }
        $this->identity = $this->authService->getIdentity();
        $this->setDi();
    }

    protected function getIdentity()
    {
        if (!$this->identity) {
            return $this->plugin('identity');
        }
        return $this->identity;
    }

    /**
     * @return Injector
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param Injector $di
     * @return AbstractAppController
     */
    public function setDi($di = null)
    {
        if (!$this->di) {
            if ($di) {
                $this->di = $di;
                return $this;
            }
            $this->di = new Injector();
        }
        return $this;
    }

}