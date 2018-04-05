<?php


namespace core\parsers\bch\elements;


use core\services\exports\XmlElement;

class Storage extends XmlElement
{
    public function __construct($type, $value)
    {
        parent::__construct('Остаток', $value, ['Склад' => $type]);
    }
}