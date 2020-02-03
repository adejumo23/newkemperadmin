<?php
/**
 * Date: 2/2/2020
 * Time: 8:49 PM
 */

namespace App\Model\Repository;


use App\Db\Connection;
use App\Di\InjectableInterface;

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
     * @return \Zend\Db\Adapter\Driver\ResultInterface|null
     */
    public function executeQuery($sql, $params = [])
    {
        return $this->connection->executeQuery($sql, $params);
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