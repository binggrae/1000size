<?php


namespace core\pages\east;


use core\elements\east\Product;
use core\pages\Page;

class ProductPage extends Page
{
    const URL = 'http://dealer.eastmarine.ru/shop/search';

    /** @var \phpQueryObject */
    private $pq;

    private $barcode;

    public function __construct($barcode, $html)
    {
        $this->pq = \phpQuery::newDocumentHTML($html);
        $this->barcode = $barcode;
    }

    public function getData()
    {
        $tr = $this->pq->find('.catalog .details');

        if (!$tr->count()) {
            return null;
        }

        foreach ($tr as $item) {
            $pq = pq($item);
            if ($pq->find('.parts:eq(0)')->text() == $this->barcode) {
                $product = new Product();
                $product->barcode = $this->barcode;
                $product->title = trim(explode('function', $pq->find('.description')->text())[0]);

                foreach ($pq->find('.refuse:eq(0) .rf') as $storage) {
                    if(!$storage) {
                        break;
                    }
                    $text = explode(' : ', pq($storage)->text());
                    if ($text[0] == 'Основной склад') {
                        $product->storageV = (int)$text[1];
                    }
                }

                foreach ($pq->find('.price1:eq(0) .pr') as $price) {
                    $text = explode(' : ', pq($price)->text());
                    if ($text[0] == 'Оптовая') {
                        $product->purchase = (float)$text[1];
                    }
                    if ($text[0] == 'Розничная') {
                        $product->retail = (float)$text[1];
                    }
                }

                return $product;
            }
        }

        return null;
    }
}