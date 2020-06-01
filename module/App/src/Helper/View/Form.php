<?php


namespace App\Helper\View;


class Form extends AbstractViewHelper
{
    /**
     * @param \App\Form\Element\Form $element
     * @return string
     */
    public function render($element)
    {
        $element->addAttribute('method', $element->getMethod());
        $element->addAttribute('action', $element->getAction());
        $filters = $element->getFilters();
        $formElements = $element->getElements();
        $element->setElements([]);
        foreach ((array)$filters as $filter) {
            $element->addElement($filter);
        }
        foreach ((array)$formElements as $formElement) {
            $element->addElement($formElement);
        }
        return parent::render($element);
    }

}