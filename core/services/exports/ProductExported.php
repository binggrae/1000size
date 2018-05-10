<?php


namespace core\services\exports;


abstract class ProductExported
{
    /** @var XmlElement */
    public $barcode;

    /** @var XmlElement */
    public $title;

    /** @var XmlElement */
    public $purchase;

    /** @var XmlElement */
    public $retail;

    /** @var XmlElement */
    public $unit;

    public $_attributes = [];

    public $factor;

    public function __construct($barcode, $factor)
    {
        $this->factor = $factor;

        $this->barcode = new XmlElement('Артикул', $barcode);
        $this->title = new XmlElement('Наименование');
        $this->unit = new XmlElement('ЕдИзм');
        $this->purchase = new XmlElement('ЦенаДилерская');
        $this->retail = new XmlElement('ЦенаРозничная');
    }

    public function export($fields)
    {
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

    public function getRetail()
    {
        $purchase = ((double)str_replace(',', '.', $this->purchase->value));
        $retail = round($purchase * $this->factor, 2);

        $this->retail->set(max($retail, $this->retail->value));

        return $this->retail;
    }

    public function getXmlAttributes()
    {
        return $this->_attributes;
    }

    public function setXmlAttribute ($name, $value)
    {
        $this->_attributes[$name] = $value;
    }
}