<?php


namespace core\parsers\size\elements;

use core\entities\size\Products;
use core\services\exports\ProductExported;
use core\services\exports\XmlElement;

class Product extends ProductExported
{

    /** @var XmlElement  */
    public $barcode;

    /** @var XmlElement  */
    public $unit;

    /** @var XmlElement[]  */
    public $storage = [];

    public function __construct(Products $product, $factor)
    {
        parent::__construct($product->barcode, $factor);
        $this->unit->set($product->unit);
        $this->title->set($product->title);
        $this->purchase->set($product->purchase);
        $this->retail->set($product->retail);
        $this->storage['m'] = new XmlElement('Остаток', $product->storageM, ['Склад' => 'Склад Москва']);
        $this->storage['v'] = new XmlElement('Остаток', $product->storageV, ['Склад' => 'Склад Владивосток']);

        $this->setXmlAttribute('ДатаИзменения', date('n/d/Y H:i:s A', $product->load_ts));
    }



}