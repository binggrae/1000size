<?php


namespace core\parsers\size\pages;


use core\elements\size\Product;

class ProductPage extends Page implements CatalogPage
{

    public $barcode;
    public $unit;
    public $storageHtml;


    public function getLink($barcode)
    {
        return $this->pq->find('link[rel="canonical"]')->attr('href');
    }

    public function getTitle()
    {
        return trim($this->pq->find('.s-nomenclature__name')->text());
    }


    public function getUnit()
    {
        $this->getStorageHtml();
        return $this->unit;
    }

    public function getStorageM()
    {
        return $this->getStorageHtml()['m'];
    }

    public function getStorageV()
    {
        return $this->getStorageHtml()['v'];
    }

    public function getStorageHtml()
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

    public function getPurchase()
    {
        return (int)trim($this->pq->find('.s-nomenclature__price')->attr('content'));
    }

    public function getRetail()
    {
        return (int)preg_replace("/[^0-9]/", '', $this->pq->find('.s-nomenclature__baseprice')->text());
    }
}