<?php


namespace core\services\techno;


use core\entities\ProductInterface;
use core\services\Xml as BaseXml;

class Xml extends BaseXml
{

    /**
     * TechnoXml constructor.
     * @param $date
     * @param ProductInterface[] $products
     */
    public function __construct($date, array $products)
    {
        $this->date['ДатаСозданияТМ'] = $date;
        parent::__construct($products);
    }
}