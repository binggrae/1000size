<?php


namespace core\pages\size;


use core\elements\size\Product;

class ProductPage
{

    private $pq;

    private $storageHtml;

    private $unit;

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
        return trim($this->pq->find('.s-nomenclature__articul span[itemprop="sku"')->text());
    }

    private function getTitle()
    {
        return trim($this->pq->find('.s-nomenclature__name')->text());
    }

    public function getUnit()
    {
        $this->getStorageHtml();
        return $this->unit;
    }

    private function getStorageM()
    {
        return $this->getStorageHtml()['m'];
    }

    private function getStorageV()
    {
        return $this->getStorageHtml()['v'];
    }

    private function getStorageHtml()
    {
        if (!$this->storageHtml) {
            $pq = $this->pq->find('.s-catalog-groups__availability');

            $result = ['m' => 0, 'v' => 0];
            foreach ($pq as $item) {
                $val = explode(' ', pq($item)->find('.s-catalog-groups__availability-amount')->text());
                $this->unit = array_pop($val);
                $val = implode('', $val);
                if (pq($item)
                        ->find('.s-catalog-groups__availability-branch')
                        ->text() == 'Склад Владивосток') {
                    $result['v'] = (int)$val;
                } elseif (pq($item)
                        ->find('.s-catalog-groups__availability-branch')
                        ->text() == 'Склад Москва') {
                    $result['m'] = (int)$val;
                }
            }
            $this->storageHtml = $result;
        }

        return $this->storageHtml;
    }

    private function getPurchase()
    {
        return (int)trim($this->pq->find('.s-nomenclature__price')->attr('content'));
    }

    private function getRetail()
    {
        return (int)preg_replace("/[^0-9]/", '', $this->pq->find('.s-nomenclature__baseprice')->text());
    }

    private function getBrand()
    {
        return trim($this->pq->find('.s-nomenclature__attribute-value[itemprop="brand"]')->text());
    }

    private function getCountry()
    {
        $countryHtml = $this->pq->find('#list_attributes')->html();
        $countryHtml = preg_replace("/  +/", " ", $countryHtml);
        $countryHtml = str_replace("\n", "", $countryHtml);
        $countryHtml = str_replace("\r", "", $countryHtml);

        preg_match('/>Страна производителя<\/th> <td class="s-nomenclature__attribute-value">([\w\s]+)/u', $countryHtml, $math);
        return !empty($math) ? str_replace(' ', '', $math[1]) : '';
    }
}