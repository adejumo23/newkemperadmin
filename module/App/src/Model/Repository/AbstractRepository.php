<?php
/**
 * Date: 2/2/2020
 * Time: 8:49 PM
 */

namespace App\Model\Repository;


use App\Db\Connection;
use App\Di\InjectableInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class AbstractRepository implements InjectableInterface
{

    protected $di;

    /**
     * @var Connection
     * @Inject(name="App\Db\Connection")
     */
    protected $connection;

    public function findBy()
    {
        //Todo: implement findBy
    }

    /**
     * @param $sql
     * @param array $params
     * @return array|null
     */
    public function executeQuery($sql, $params = [])
    {
        $result = $this->connection->executeQuery($sql, $params);
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);
            $data = $resultSet->toArray();
            return $data;
        }
        return null;
    }

    /**
     * @param Connection $connection
     * @return AbstractRepository
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
        return $this;
    }



    public function setDi($di)
    {
        $this->di = $di;
    }
}