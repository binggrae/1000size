<?php


namespace core\entities\automaster;

use core\services\exports\ProductExported;
use core\services\exports\XmlElement;

class Product extends ProductExported
{
    public $storage;
    public $brand;

    public function __construct($barcode, $factor)
    {
        parent::__construct($barcode, $factor);
        $this->brand = new XmlElement('Производитель');
        $this->storage = new XmlElement('Остаток', 0, ['Склад' => 'Склад Москва']);
    }

}