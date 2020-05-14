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
        $firstName = $identity->getFirstname();
        $lastName = $identity->getLastname();
        $privilege_conservation_dashboard_view = $identity->getPriv('conservation_dashboard_view');
        $privilege_conservation_listings = $identity->getPriv('conservation_listings');
        $privilege_production_dashboard_view = $identity->getPriv('production_dashboard_view');
        $privilege_production_reports = $identity->getPriv('production_reports');
        $privilege_conservation_reports = $identity->getPriv('conservation_reports');
        $this->layout()->firstName =  $firstName;
        $this->layout()->privilege_conservation_dashboard_view =  $privilege_conservation_dashboard_view;
        $this->layout()->privilege_conservation_listings =  $privilege_conservation_listings;
        $this->layout()->privilege_production_dashboard_view =  $privilege_production_dashboard_view;
        $this->layout()->privilege_production_reports =  $privilege_production_reports;
        $this->layout()->privilege_conservation_reports =  $privilege_conservation_reports;
        $data['firstName'] = $firstName;
        $data['lastName'] = $lastName;
        $data['privilege_conservation_dashboard_view'] = $privilege_conservation_dashboard_view;
        $data['privilege_conservation_listings'] = $privilege_conservation_listings;
        $data['privilege_production_dashboard_view'] = $privilege_production_dashboard_view;
        $data['privilege_production_reports'] = $privilege_production_reports;
        $data['privilege_conservation_reports'] = $privilege_conservation_reports;
        return new ViewModel($data);
    }
}