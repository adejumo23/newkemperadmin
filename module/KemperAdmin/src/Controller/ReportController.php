<?php
/**
 * Date: 1/21/2020
 * Time: 10:26 PM
 */

namespace KemperAdmin\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Db\Adapter;

class ReportController extends AbstractActionController
{


    public function indexAction()
    {
//        $conn = new Adapter\Driver\Sqlsrv\Connection();
//        $conn->connect();
//        $result = $conn->execute($query);
//        while ($row = $result->next()) {
//            $data[] = $row;
//        }
        $data = [
            'blah',
            'blah',
        ];
        return new ViewModel(['template' => 'kemperadmin/report/index'], ['data' => $data]);
    }
}