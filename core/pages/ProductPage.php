<?php


namespace core\pages;


use core\elements\Product;

class ProductPage
{

    private $pq;

    private $storageHtml;

    public function __construct($html)
    {
        $this->pq = \phpQuery::newDocumentHTML($html);
    }

    public function getData()
    {
        $product = new Product();
        $product->barcode = $this->getBarcode();
        $product->title = $this->getTitle();

        $product->unit = $this->getUnit();
        $product->storageM = $this->getStorageM();
        $product->storageV = $this->getStorageV();

        $product->purchase = $this->getPurchase();
        $product->retail = $this->getRetail();

        $product->brand = $this->getBrand();
        $product->country = $this->getCountry();

        return $product;
    }

    private function getBarcode()
    {
        return trim($this->pq->find('.s-nomenclature__articul')->text());
    }

    private function getTitle()
    {
        return trim($this->pq->find('.s-nomenclature__name')->text());
    }

    public function getUnit()
    {
        return trim($this->pq->find('.s-text__mr_10:eq(0)')->html());
    }

    private function getStorageM()
    {
        $storageHtml = $this->getStorageHtml();

        preg_match('/>Склад Москва<\/span> <\/td> <td class="s-definition__value s-definition__value_text_right"> <span class="s-definition__value-inner"> ([\d\s]+)/u', $storageHtml, $math);
        return !empty($math) ? (int)str_replace(' ', '', $math[1]) : 0;
    }

    private function getStorageV()
    {
        $storageHtml = $this->getStorageHtml();

        preg_match('/>Склад Владивосток<\/span> <\/td> <td class="s-definition__value s-definition__value_text_right"> <span class="s-definition__value-inner"> ([\d\s]+)/u', $storageHtml, $math);
        return !empty($math) ? (int)str_replace(' ', '', $math[1]) : 0;
    }

    private function getStorageHtml()
    {
        if (!$this->storageHtml) {
            $storageHtml = $this->pq->find('.s-definition tbody:eq(0)')->html();
            $storageHtml = preg_replace("/  +/", " ", $storageHtml);
            $storageHtml = str_replace("\n", "", $storageHtml);
            $storageHtml = str_replace("\r", "", $storageHtml);

            $this->storageHtml = $storageHtml;
        }

        return $this->storageHtml;
    }

    private function getPurchase()
    {
        return (int) trim($this->pq->find('.s-nomenclature__price')->attr('content'));
    }

    private function getRetail()
    {
        return (int) preg_replace("/[^0-9]/", '', $this->pq->find('.s-nomenclature__baseprice')->text());
    }

    private function getBrand()
    {
        return trim($this->pq->find('.s-nomenclature__attribute-value[itemprop="brand"]')->text());
    }

    private function getCountry()
    {
        $countryHtml = $this->pq->find('#nomenclature_attributes')->html();
        $countryHtml = preg_replace("/  +/", " ", $countryHtml);
        $countryHtml = str_replace("\n", "", $countryHtml);
        $countryHtml = str_replace("\r", "", $countryHtml);

        preg_match('/>Страна производителя<\/th> <td class="s-nomenclature__attribute-value">([\w\s]+)/u', $countryHtml, $math);
        return !empty($math) ? str_replace(' ', '', $math[1]) : '';
    }
}