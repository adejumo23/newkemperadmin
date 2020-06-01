<?php
/**
 * Date: 2/2/2020
 * Time: 8:49 PM
 */

namespace App\Model\Repository;


use App\Db\Connection;
use App\Di\InjectableInterface;
use App\Model\Entity\AbstractEntity;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class AbstractRepository implements InjectableInterface
{
    const SQL_SERVER_DATE_FORMAT = 'Y-m-d';

    /**
     * @var Connection
     * @Inject(name="App\Db\Connection")
     */
    protected $connection;

    /** @var string */
    protected $entityClassName;
    /**
     * @var AbstractEntity
     */
    protected $entity;

    /**
     * @var array
     */
    protected $templateMap = [];



    /**
     * @param $sql
     * @param array $params
     * @return array|null|int
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
        if ($this->connection->getDb()->getDriver()->getLastGeneratedValue() > 0) {
            return $this->connection->getDb()->getDriver()->getLastGeneratedValue();
        }
        return null;
    }


    public function save($entity)
    {
        if (method_exists($entity, 'preSaveHook')) {
            $entity->preSaveHook();
        }
        return $this->insert($entity);
    }

    /**
     * @param array|null $criteria
     * @param int|null $limit
     * @param int $offset
     * @return array
     */
    public function findBy(array $criteria = null, int $limit = null, int $offset = 0)
    {
        $return = [];
        $selects = $this->entity->getFields();
        $selectString = implode(', ', array_keys($selects));
        if ($criteria) {
            $whereString = $this->getWhereString($criteria);
            $query = <<<SQL
      SELECT {$selectString} FROM {$this->entity->getTable()} WHERE {$whereString} 
SQL;
        } else {
            $query = <<<SQL
      SELECT {$selectString} FROM {$this->entity->getTable()} 
SQL;
        }

        if ($limit) {
            $query .= <<<SQL
            LIMIT {$offset}, {$limit}
SQL;
        }
        $result = mysqli_query($this->connection, $query);
        if (mysqli_affected_rows($this->connection) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $return[] = $row;
            }
        } else {
            error_log(mysqli_error($this->connection));
        }
        return $return;
    }

    /**
     * @param array $criteria
     * @return array|null
     */
    public function findOneBy(array $criteria)
    {
        $return = $this->findBy($criteria, 1);
        return reset($return);
    }


    /**
     * @param array $criteria
     * @return int
     */
    public function deleteBy(array $criteria)
    {
        $criteria = $this->prepareCriteria($criteria);
        $whereString = $this->getWhereString($criteria);
        $query = <<<SQL
      DELETE FROM {$this->entity->getTable()} WHERE {$whereString} 
SQL;
        $result = mysqli_query($this->connection, $query);
        if ($result) {
            $return = 1;
        } else {
            error_log(mysqli_error($this->connection));
            $return = 0;
        }
        return $return;
    }

    /**
     * @param array $updateSet
     * @return int
     */
    public function update(array $updateSet)
    {
        $criteria = $this->prepareCriteria($updateSet);
        if (empty($criteria)) {
            return 0;
        }

        $whereString = $this->getWhereString($criteria);
        $updateString = $this->getUpdateString($updateSet);
        $query = <<<SQL
      UPDATE {$this->entity->getTable()} SET {$updateString} WHERE {$whereString} 
SQL;
        $result = mysqli_query($this->connection, $query);

        if (!$result) {
            $err = mysqli_error($this->connection);
            error_log($err);
            return 0;
        }
        return $result;
    }

    /**
     * @param array $insertSet
     * @return bool|int|\mysqli_result
     * @throws \Exception
     */
    public function insert($insertSet)
    {
        if (is_object($insertSet)) {
            $insertString = $this->getInsertStringForEntity($insertSet);
        }else {
            $insertString = $this->getInsertString($insertSet);
        }
        $fieldString = $this->getFieldString();
        $query = <<<SQL
INSERT INTO {$this->entity->getTable()} ({$fieldString}) VALUES ({$insertString})  
SQL;
        $return = $this->executeQuery($query);
        return $return;
    }

    /**
     * @param array $insertSet
     * @return string
     */
    private function getInsertString(array $insertSet)
    {
        $insertString = '';
        foreach ($insertSet as $item) {
            if (is_array($item)) {
                $insertString .= $this->getInsertString($item) . '), (';
            }
        }
        foreach ($this->entity->getFields() as $fieldname => $metadata) {
            if ($metadata['identifier']) {
                unset($insertSet[$fieldname]);
                continue;
            }
            $insertString .= (isset($insertSet[$fieldname]) ? '\'' . $insertSet[$fieldname] . '\'' : 'NULL') . ', ';
        }

        return rtrim($insertString, ', ');
    }

    /**
     * @param array $insertSet
     * @return string
     */
    private function getInsertStringForEntity($entity)
    {
        $insertString = '';
        foreach ($this->entity->getFields() as $fieldname => $metadata) {
            if ($metadata['identifier']) {
                continue;
            }
            $getter = 'get' . $metadata['propertyName'];
            if (!method_exists($entity, $getter)) {
                throw new \Exception('Getter not found in entity('.get_class($entity).'): ' . $getter);
            }
            if ($metadata['type'] == 'datetime') {
                $insertString .= ($entity->{$getter}() ? '\'' . $entity->{$getter}()->format(self::SQL_SERVER_DATE_FORMAT) . '\'': 'NULL') . ', ';
            }else {
                $insertString .= ($entity->{$getter}() ? '\'' . $entity->{$getter}() . '\'' : 'NULL') . ', ';
            }
        }

        return rtrim($insertString, ', ');
    }

    /**
     * @return string
     */
    private function getFieldString()
    {
        $fieldString = '';
        foreach ($this->entity->getFields() as $fieldname => $metadata) {
            if (!isset($metadata['identifier']) || !($metadata['identifier'])) {
                $fieldString .= $fieldname . ', ';
            }
        }
        return rtrim($fieldString, ', ');
    }

    /**
     * @param array|null $criteria
     * @param string $separator
     * @return string
     */
    private function getWhereString(array $criteria = null, string $separator = ' AND ')
    {
        $whereString = '';
        foreach ($criteria as $col => $data) {
            $whereString .= $col . '= \'' . $data . '\'' . $separator;
        }
        return rtrim($whereString, $separator);
    }

    /**
     * @param array $updateSet
     * @return string
     */
    private function getUpdateString(array $updateSet)
    {
        return $this->getWhereString($updateSet, ' , ');
    }
    /**
     * @param $dataSet
     * @return array
     */
    private function prepareCriteria(&$dataSet)
    {
        $fields = $this->entity->getFields();
        $criteria = [];
        foreach ($fields as $fieldname => $metadata) {
            if ($metadata['identifier']) {
                $criteria[$fieldname] = $dataSet[$fieldname];
                unset($dataSet[$fieldname]);
            }
        }
        return $criteria;
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

    /**
     * @param AbstractEntity $entity
     * @return AbstractRepository
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }
}