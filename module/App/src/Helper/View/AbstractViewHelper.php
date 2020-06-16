<?php


namespace App\Helper\View;


use App\Di\ContainerAwareInterface;
use App\Di\View\FormFactory;
use App\Form\Element\AbstractElement;
use Interop\Container\ContainerInterface;
use KemperAdmin\Form\Filter\AbstractFilter;
use Zend\View\Helper\AbstractHelper;

/**
 * Class AbstractViewHelper
 * @package App\Helper\View
 * @method string|null basePath($file = null)
 * @method \Zend\View\Helper\InlineScript inlineScript($mode = \Zend\View\Helper\HeadScript::FILE, $spec = null, $placement = 'APPEND', array $attrs = array(), $type = 'text/javascript')
 */
class AbstractViewHelper implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    /**
     * @var \Zend\View\HelperPluginManager
     */
    protected $viewHelperManager;


    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render($element)
    {
        $nameString = $this->getNameString($element);
        $idString = $this->getIdString($element);
        $classString = $this->getClassString($element);
        $attributeString = $this->getAttributeString($element);
        $childElements = $this->getChildElementsHtml($element);
        $labelStart = $this->getLabelStart($element);
        $labelEnd = $this->getLabelEnd($element);
        $html = <<<HTML
{$labelStart}
{$labelEnd}
<{$element->getTag()} {$nameString} {$idString} {$classString} {$attributeString} >
{$childElements}
</{$element->getTag()}>

HTML;
        return $html;
    }

    /**
     * @param \App\Form\Element\Input $element
     * @return string
     */
    protected function getLabelStart($element)
    {
        $labelString = "";
        if ($element->getLabel()) {
            $labelString =<<<HTML
<label for="{$element->getName()}">{$element->getLabel()}:&nbsp;&nbsp;&nbsp;&nbsp;
HTML;
        }
        return $labelString;
    }

    /**
     * @param \App\Form\Element\Input $element
     * @return string
     */
    protected function getLabelEnd($element)
    {
        $labelString = "";
        if ($element->getLabel()) {
            $labelString =<<<HTML

</label>
HTML;
        }
        return $labelString;

    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function getNameString($element)
    {
        $name = $element->getName();
        if ($name && !empty($name)) {
            return 'name="' . $name . '"';
        }
        return "";
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function getIdString($element)
    {
        $id = $element->getId();
        if ($id && !empty($id)) {
            return 'id="' . $id . '"';
        }
        return "";
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function getClassString($element)
    {
        $classes = $element->getClasses();
        if ($classes && count($classes)) {
            return 'class="' . implode(" ", $classes) . '"';
        }
        return "";
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function getAttributeString($element)
    {
        $attrString = "";
        $attrs = $element->getAttributes();
        foreach ((array)$attrs as $attrname => $attrVal) {
            $attrString .= $attrname . "=\"" . $attrVal . "\" ";
        }
        if ($element->isHidden()) {
            $attrString .= " hidden ";
        }
        return $attrString;
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function getChildElementsHtml($element)
    {
        $childElementsString = "";
        $childElements = $element->getElements();
        /** @var AbstractElement $childElement */
        foreach ((array)$childElements as $childElement) {
            if ($childElement instanceof AbstractFilter) {
                $childElementHtml = $childElement->renderHtml();
            }else {
                $elementType = get_class($childElement);
                $viewHelper = $this->container->get($elementType);
                if ($viewHelper) {
                    $childElementHtml = $viewHelper->render($childElement);
                } else {
                    throw new \Exception("View Helper missing for Form Element: " . $elementType);
                }
            }
            $childElementsString .= $childElementHtml;
        }
        if ($element->getText()) {
            $childElementsString .= $element->getText();
        }
        return $childElementsString;
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

    /**
     * Get plugin instance
     *
     * @param  string     $name Name of plugin to return
     * @param  null|array $options Options to pass to plugin constructor (if not already instantiated)
     * @return AbstractHelper
     */
    public function plugin($name, array $options = null)
    {
        return $this->getViewHelperManager()->get($name, $options);
    }

    /**
     * Overloading: proxy to helpers
     *
     * Proxies to the attached plugin manager to retrieve, return, and potentially
     * execute helpers.
     *
     * * If the helper does not define __invoke, it will be returned
     * * If the helper does define __invoke, it will be called as a functor
     *
     * @param  string $method
     * @param  array $argv
     * @return mixed
     */
    public function __call($method, $argv)
    {
        $plugin = $this->plugin($method);

        if (is_callable($plugin)) {
            return call_user_func_array($plugin, $argv);
        }

        return $plugin;
    }

    /**
     * @param \Zend\View\HelperPluginManager $viewHelperManager
     * @return $this
     */
    public function setViewHelperManager($viewHelperManager)
    {
        $this->viewHelperManager = $viewHelperManager;
        return $this;
    }

    protected function getViewHelperManager()
    {
        return $this->viewHelperManager;
    }
}