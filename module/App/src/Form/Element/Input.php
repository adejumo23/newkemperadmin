<?php


namespace App\Form\Element;


class Input extends AbstractElement
{
    protected $tag = "input";

    protected $attributes = [
        'type' => 'text',
    ];

}