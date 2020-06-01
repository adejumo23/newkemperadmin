<?php


namespace KemperAdmin\Form\Element;


use App\Di\InjectableInterface;
use App\Form\Element\AbstractElement;
use App\Form\Element\ListIndex;
use App\Form\Element\UnorderedList;

class HierarchyList extends UnorderedList
{

    /**
     * @var string[]
     */
    private $anchorClasses;

    public function addOption($value, $text)
    {

        $hierarchyListItem = new HierarchyListItem();
        $hierarchyListItem->addClass('heirarchy');
        $hierarchyListItem->setAnchorClasses($this->anchorClasses);

        $hierarchyListItem->create($value, $text);
        $this->addElement($hierarchyListItem);
        return $hierarchyListItem;

    }

    /**
     * @param string $class
     * @return $this
     */
    public function addOptionsClass($class)
    {
        $this->anchorClasses[] = $class;
        return $this;
    }



}