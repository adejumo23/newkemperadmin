<?php


namespace KemperAdmin\Form\Element;


use App\Form\Element\Anchor;
use App\Form\Element\ListIndex;

class HierarchyListItem extends ListIndex
{

    /**
     * @var array
     */
    protected $anchorClasses;

    /**
     * @param string $value
     * @param string $text
     */
    public function create($value, $text)
    {
        $dataItem = new Anchor();
        $dataItem->addClass('dropdown-item');
        foreach ($this->anchorClasses as $anchorClass) {
            $dataItem->addClass($anchorClass);
        }
        $dataItem->addAttribute('data-listitemvalue', $value);
        $dataItem->setText($text);
        $this->addElement($dataItem);
    }

    public function setAnchorClasses(array $anchorClasses)
    {
        $this->anchorClasses = $anchorClasses;
        return $this;
    }
}