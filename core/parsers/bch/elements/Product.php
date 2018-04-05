<?php


namespace core\parsers\bch\elements;


use core\parsers\bch\Api;
use core\services\exports\ProductExported;
use core\services\exports\XmlElement;

class Product extends ProductExported
{
    /** @var string  */
    public $link;

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

    public function __construct($link)
    {
        $this->link = Api::URL . urlencode($link);

        $this->barcode = new XmlElement('Артикул');
        $this->title = new XmlElement('Наименование');
        $this->unit = new XmlElement('ЕдИзм', 'шт.');
        $this->purchase = new XmlElement('ЦенаДилерская');
        $this->retail = new XmlElement('ЦенаРозничная');
    }


}