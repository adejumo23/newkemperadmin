<?php


namespace App\Form\Element;


use KemperAdmin\Form\Filter\AbstractFilter;

class Form extends AbstractElement
{

    protected $tag = "form";
    protected $action;
    protected $method;
    /**
     * @var AbstractFilter[]
     */
    protected $filters;

    public function __construct($name = "", $action="", $method = "POST")
    {
        $this->setName($name);
        $this->setAction($action);
        $this->setMethod($method);
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     * @return Form
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     * @return Form
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param array $filters
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;
        return $this;

    }

    /**
     * @return AbstractFilter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

}