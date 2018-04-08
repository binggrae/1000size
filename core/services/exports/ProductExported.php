<?php


namespace core\services\exports;


abstract class ProductExported
{
    /** @var XmlElement  */
    public $barcode;

    public function export($fields) {
        $result = [];
        foreach ($fields as $field) {
            $method = 'get' . ucfirst($field);
            $result[$field] = method_exists($this, $method) ? $this->{$method}() : $this->{$field};
        }
        return $result;
    }

    public function isLoad()
    {
        return !!$this->barcode->value;
    }

}