<?php


namespace core\services\exports;


class XmlElement
{
    public $name;

    public $value;

    /** @var array */
    public $attributes;

    public function __construct($name, $value = '', $attributes = [])
    {
        $this->name = $name;
        $this->attributes = $attributes;
        $this->set($value);
    }

    public function set($value)
    {
        $this->value = str_replace('&', '_', $value);
    }


}