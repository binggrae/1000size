<?php


namespace core\parsers\power\elements;


use core\services\exports\XmlElement;

class Storage extends XmlElement
{

    public function __construct($type, $value = 0)
    {
        parent::__construct('Остаток', $value, ['Склад' => $type]);
    }
}