<?php
/**
 * Date: 1/21/2020
 * Time: 10:25 PM
 */

namespace KemperAdmin\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }
}