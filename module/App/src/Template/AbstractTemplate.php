<?php


namespace App\Template;


use App\Di\ContainerAwareInterface;
use App\Di\InjectableInterface;
use App\Di\View\FormFactory;
use App\Form\Element\AbstractElement;
use Interop\Container\ContainerInterface;

/**
 * Class AbstractTemplate
 * @package App\Template
 */
abstract class AbstractTemplate implements ContainerAwareInterface, InjectableInterface
{

    /**
     * @var string
     */
    protected $html;

    /**
     * @var AbstractElement
     */
    protected $htmlElement;

    /**
     * @var mixed
     */
    protected $data;
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @return string
     */
    public function getHtml()
    {
        if (!$this->html) {
            $this->createHtml();
            $elementViewHelper = $this->container->get(get_class($this->htmlElement));
            $this->html = $elementViewHelper->render($this->htmlElement);
        }
        return $this->html;
    }

    /**
     * @param string $html
     * @return $this
     */
    protected function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    /**
     * @return mixed
     * set the html property in the overriden functions
     */
    protected abstract function createHtml();

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return AbstractTemplate
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return AbstractElement
     */
    public function getHtmlElement()
    {
        return $this->htmlElement;
    }

    /**
     * @param AbstractElement $htmlElement
     * @return AbstractTemplate
     */
    public function setHtmlElement($htmlElement)
    {
        $this->htmlElement = $htmlElement;
        return $this;
    }

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }


}