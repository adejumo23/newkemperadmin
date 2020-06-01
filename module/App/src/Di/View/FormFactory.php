<?php


namespace App\Di\View;



use App\Di\ContainerAwareInterface;
use App\Form\Element\Anchor;
use App\Form\Element\Button;
use App\Form\Element\Form;
use App\Form\Element\SubmitButton;
use App\Form\Element\ElementGroup;
use App\Helper\View\AbstractViewHelper;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use KemperAdmin\Form\Element\HierarchyList;
use KemperAdmin\Form\Element\HierarchyListItem;
use KemperAdmin\Helper\View\HierarchyList as HLView;
use KemperAdmin\Helper\View\HierarchyListItem as HLIView;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\View\HelperPluginManager;

class FormFactory implements AbstractFactoryInterface
{

    protected static $classMap = [
        Form::class => \App\Helper\View\Form::class,
        HierarchyList::class => HLView::class,
        HierarchyListItem::class => HLIView::class,
        Anchor::class => \App\Helper\View\Anchor::class,
        Button::class => AbstractViewHelper::class,
        ElementGroup::class => AbstractViewHelper::class,
        SubmitButton::class => AbstractViewHelper::class,

    ];
    /**
     * @var HelperPluginManager
     */
    private $viewHelperManager;

    /**
     * Can the factory create an instance for the service?
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return array_key_exists($requestedName, self::$classMap);
    }

    /**
     * Create an object
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return string
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        if (!$this->viewHelperManager) {
            $this->viewHelperManager = $container->get('ViewHelperManager');
        }
        /** @var AbstractViewHelper $viewHelper */
        $viewHelper = new self::$classMap[$requestedName]();
        $viewHelper->setViewHelperManager($this->viewHelperManager);
        if ($viewHelper instanceof ContainerAwareInterface) {
            $viewHelper->setContainer($container);
        }
        return $viewHelper;
    }

    public static function getViewHelperType($element)
    {
        $elementType = get_class($element);
        return self::$classMap[$elementType];
    }
}