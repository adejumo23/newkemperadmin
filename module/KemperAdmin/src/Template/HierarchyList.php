<?php


namespace KemperAdmin\Template;


use App\Template\AbstractTemplate;

class HierarchyList extends AbstractTemplate
{
    protected $url;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var array
     */
    protected $dataAttributes;
    /**
     * @var bool
     */
    protected $hidden = false;

    public function getListHtml()
    {
        $this->createHtml();
        return $this->getHtml();
    }



    protected function createHtml()
    {
        $hierarchyList = new \KemperAdmin\Form\Element\HierarchyList($this->getName());
        $hierarchyList->addDataAttribute("url", $this->url);
        $hierarchyList->setHidden($this->hidden);
        $hierarchyList->setClasses(["updatedHierarchy","collapsible", "collapsible-accordion"]);
        $hierarchyList->addOptionsClass("managerSelectListSideBar");
        foreach ((array)$this->data as $value => $item) {
            $hierarchyList->addOption($value, $item);
        }
        foreach ((array)$this->dataAttributes as $attributeName => $attributeValue) {
            $hierarchyList->addDataAttribute($attributeName, $attributeValue);
        }

        $this->setHtmlElement($hierarchyList);
    }


    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        if (!$this->name) {
            $this->name = uniqid("hierarchyList");
        }
        return $this->name;
    }
    /**
     * @param mixed $url
     * @return HierarchyList
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param array $dataAttributes
     * @return $this
     */
    public function setDataAttributes($dataAttributes)
    {
        $this->dataAttributes = $dataAttributes;
        return $this;
    }

    /**
     * @param bool $hidden
     * @return $this
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }
}