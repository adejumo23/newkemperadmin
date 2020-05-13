<?php
/**
 * Date: 1/21/2020
 * Time: 10:25 PM
 */

namespace KemperAdmin\Controller;
use App\AbstractAppController;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractAppController
{

    public function indexAction()
    {
        $identity = $this->getIdentity();
        return new ViewModel();
    }
}