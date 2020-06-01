<?php


namespace KemperAdmin\Form\Filter;


use App\Di\ContainerAwareInterface;
use App\Di\InjectableInterface;
use Interop\Container\ContainerInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\View\Renderer\PhpRenderer;

abstract class AbstractFilter implements InjectableInterface, ContainerAwareInterface
{
    protected $name;
    protected $settings;
    protected $defaults;
    protected $description;
    protected $container;

    abstract protected function getHtml();

    protected function getJs()
    {
        return "";
    }
    public function renderHtml()
    {
        $html = $this->getHtml();
        $js = $this->getJs();

        /** @var Request $request */
        $request = $this->container->get('request');
        /** @var PhpRenderer $viewRenderer */
        $viewRenderer = $this->container->get('ViewRenderer');

//        $inlineScript = $pm->get('Zend\View\Helper\InlineScript');
        /** @var Zend\View\Helper\InlineScript $inlineScript */
//        $inlineScript = $this->container->get('Zend\View\Helper\InlineScript');

        if (is_array($js)) {
            foreach ($js as $jsScript) {
                $file = $viewRenderer->basePath($jsScript);
                $viewRenderer->inlineScript()->prependFile($file);
            }
        }else{
            $file = $request->getBasePath($js);
            $inlineScript->prependFile($file);
        }
        return $html;
    }

    /**
     * @param ContainerInterface $container
     * @return AbstractFilter
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param mixed $settings
     * @return AbstractFilter
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return AbstractFilter
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return AbstractFilter
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param mixed $defaults
     * @return AbstractFilter
     */
    public function setDefaults($defaults)
    {
        foreach ((array)$defaults as $key => $value) {
            if (is_callable($value)) {
                $this->defaults[$key] = call_user_func($value);
            }else{
                $this->defaults[$key] = $value;
            }
        }

        return $this;
    }

}