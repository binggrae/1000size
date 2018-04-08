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
    public $storages = [];

    /** @var XmlElement  */
    public $purchase;

    /** @var XmlElement  */
    public $retail;

    public function __construct($barcode)
    {
        $this->barcode = new XmlElement('Артикул', $barcode);
        $this->title = new XmlElement('Наименование');
        $this->unit = new XmlElement('ЕдИзм', 'шт.');
        $this->purchase = new XmlElement('ЦенаДилерская');
        $this->retail = new XmlElement('ЦенаРозничная');
        $this->storages = new Storage('Склад Москва');
    }

    protected function getRetail()
    {
        $this->retail->set(number_format($this->purchase->value * 1.3, 2, '.', ''));
        return $this->retail;
    }


}