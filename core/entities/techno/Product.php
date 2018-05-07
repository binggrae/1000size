<?php


namespace core\entities\techno;


use core\services\exports\ProductExported;
use core\services\exports\XmlElement;

class Product extends ProductExported
{

    public $storage;

    public function __construct($barcode, $factor)
    {
        parent::__construct($barcode, $factor);

        $this->storage = new XmlElement('Остаток', 0, ['Склад' => 'Склад Москва']);
    }
}