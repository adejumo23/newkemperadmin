<?php


namespace App\Form\Element;


class Anchor extends AbstractElement
{

    protected $tag = "a";

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->attributes['href'];
    }

    /**
     * @param string $href
     * @return Anchor
     */
    public function setHref($href)
    {
        $this->attributes["href"] = $href;
        return $this;
    }



}