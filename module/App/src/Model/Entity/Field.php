<?php


namespace App\Model\Entity;


class Field
{
    /**
     * @var string
     */
    private $propertyName;
    /**
     * @var string
     */
    private $columnName;
    /**
     * @var string
     */
    private $dataType;

    /**
     * Field constructor.
     * @param string $propertyName
     * @param string $columnName
     * @param string $datatype
     */
    public function __construct($propertyName, $columnName, $datatype)
    {
        $this->propertyName = $propertyName;
        $this->columnName = $columnName;
        $this->dataType = $datatype;
    }

}