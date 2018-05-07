<?php


namespace core\parsers\power\elements;

use core\services\exports\ProductExported;
use core\services\exports\XmlElement;

class Product extends ProductExported
{

    /** @var XmlElement  */
    public $barcode;

    /** @var XmlElement  */
    public $title;

    /** @var XmlElement  */
    public $unit;

    /** @var XmlElement[]  */
    public $storage = [];

    /** @var XmlElement  */
    public $purchase;

    /** @var XmlElement  */
    public $retail;

    public function __construct($barcode, $factor)
    {
        parent::__construct($barcode, $factor);
        $this->unit->set('шт.');
        $this->storage = new XmlElement('Остаток', 0, ['Склад' => 'Склад Москва']);
    }



}