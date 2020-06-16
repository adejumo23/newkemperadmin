<?php


namespace App\Form;


use App\Di\ContainerAwareInterface;
use App\Di\InjectableInterface;
use App\Di\View\FormFactory;
use App\Form\Element\AbstractElement;
use App\Form\Element\Form;
use Interop\Container\ContainerInterface;

/**
 * @property ContainerInterface container
 */
abstract class AbstractForm implements ContainerAwareInterface, InjectableInterface
{


    /** @var bool  */
    private $prepared = false;
    /** @var string */
    protected $name = "";
    /** @var string */
    protected $action = "";
    /** @var string */
    protected $method = "post";
    /**
     * @var Form
     */
    private $form;

    /**
     * @var mixed
     */
    protected $source;
    /**
     * @var array
     */
    protected $filters = [];


    /**
     *
     */
    public function prepare()
    {
        if (!$this->prepared) {
            $this->prepared = true;
            $this->init();
            $this->doPrepare();
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        if (!$this->prepared) {
            $this->prepare();
        }

        $formViewHelper = $this->container->get(get_class($this->form));
        return $formViewHelper->render($this->form);
    }

    /**
     *
     */
    protected function init()
    {
        $this->form = new Form($this->getName(), $this->getAction(), $this->getMethod());
        $this->form->setFilters($this->filters);
    }

    /**
     * @param AbstractElement $element
     * @return $this
     */
    public function addElement($element)
    {
        $this->form->addElement($element);
        return $this;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * Implement the doPrepare function in your child class
     */
    abstract protected function doPrepare();

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return AbstractForm
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return AbstractForm
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return AbstractForm
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param mixed $source
     * @return AbstractForm
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }


}