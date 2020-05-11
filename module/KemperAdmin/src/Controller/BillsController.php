<?php

namespace KemperAdmin\Controller;

use App\AbstractAppController;
use Zend\View\Model\ViewModel;

class BillsController extends AbstractAppController
{

    public function indexAction()
    {
        $identity = $this->getIdentity();

        return new ViewModel([
            'table' => $table,
        ]);
    }
}
