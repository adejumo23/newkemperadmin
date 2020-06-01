<?php


namespace App\Model\Entity;


use App\Di\InjectableInterface;

abstract class AbstractEntity implements InjectableInterface
{
    /**
     * @var string
     */
    protected $table = "";
    /**
     * @var array
     */
    protected $fields = [];
    /**
     * @var string
     */
    protected $repositoryClass = "";

    abstract public function setMetadata();

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $table
     * @return AbstractEntity
     */
    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return AbstractEntity
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param string $propertyName
     * @param string $columnName
     * @param string $dataType
     * @param string|null $alias
     * @param bool $identifier
     */
    public function addField(string $propertyName, string $columnName, string $dataType, string $alias = null, bool $identifier = false)
    {
        $this->fields[$columnName] = [
            'propertyName' => $propertyName,
            'type' => $dataType,
            'alias' => $alias,
            'identifier' => $identifier,
        ];
    }

    /**
     * @return string
     */
    public function getRepositoryClass()
    {
        return $this->repositoryClass;
    }

    /**
     * @param string $repositoryClass
     * @return AbstractEntity
     */
    public function setRepositoryClass($repositoryClass)
    {
        $this->repositoryClass = $repositoryClass;
        return $this;
    }

}