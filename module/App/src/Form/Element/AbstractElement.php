<?php


namespace App\Form\Element;


class AbstractElement
{

    /**
     * @var string
     */
    protected $tag;
    /**
     * @var string
     */
    protected $text;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string[]
     */
    protected $classes;
    /**
     * @var bool
     */
    protected $hidden = false;

    /**
     * @var array
     */
    protected $attributes;
    /**
     * @var AbstractElement[]
     */
    protected $elements;
    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $value;

    public function __construct($name ="", $label = "")
    {
        $this->setName($name);
        $this->setId($name);
        $this->setLabel($label);
    }


    /**
     * @param AbstractElement $element
     * @return $this
     */
    public function addElement($element)
    {
        $this->elements[] = $element;
        return $this;
    }
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return AbstractElement
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return AbstractElement
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @param string $class
     * @return $this
     */
    public function addClass($class)
    {
        $this->classes[] = $class;
        return $this;
    }

    /**
     * @param string[] $classes
     * @return AbstractElement
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * @param AbstractElement[] $elements
     * @return AbstractElement
     */
    public function setElements($elements)
    {
        $this->elements = $elements;
        return $this;
    }


    /**
     * @return string[]
     */
    public function getClasses()
    {
        return $this->classes;
    }


    /**
     * @param string $name
     * @return mixed
     */
    public function getAttribute($name)
    {
        return $this->attributes[$name];
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
        return $this;
    }
    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return AbstractElement
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function addDataAttribute($name, $value)
    {
        return $this->addAttribute("data-" . $name, $value);
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     * @return AbstractElement
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return AbstractElement[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @param string $label
     * @return $this
     */
    public  function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return AbstractElement
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     * @return AbstractElement
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return AbstractElement
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


}