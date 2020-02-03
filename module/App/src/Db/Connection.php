<?php
/**
 * Date: 2/2/2020
 * Time: 8:51 PM
 */

namespace App\Db;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Db\Adapter\Driver\ResultInterface;


class Connection
{
    private $db;

    /**
     * Connection constructor.
     */
    public function __construct()
    {
        //Todo: Read config using Config Reader
        $this->db = new DbAdapter(array(
            'driver' => 'sqlsrv',
            'hostname' => 'localhost\SQLEXPRESS',
            'username' => 'sa',
            'password' => 'tiger',
            'database' => 'kemperadmin',
        ));
    }

    /**
     * @param $sql
     * @param $params
     * @return \Zend\Db\Adapter\Driver\StatementInterface
     */
    private function createStatement($sql, $params)
    {
        return $this->db->createStatement($sql, $params);
    }

    /**
     * @param string $sql
     * @param array $params
     * @return ResultInterface|null
     */
    public function executeQuery($sql, $params)
    {
        $result = null;
        $stmt = $this->createStatement($sql, $params);
        /** @var  $preparedStmt */
        $stmt->prepare($sql);
        if ($stmt->isPrepared()) {
            /** @var ResultInterface $result */
            $result = $stmt->execute($params);
        }
        return $result;
    }

    /**
     * @return DbAdapter
     */
    public function getDb()
    {
        return $this->db;
    }



}