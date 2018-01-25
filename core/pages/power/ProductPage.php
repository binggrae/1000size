<?php


namespace core\pages\power;


use core\elements\power\Product;

class ProductPage
{
    const URL = 'http://part.m-powergroup.ru/cart/add_item';

    /** @var \phpQueryObject */
    private $pq;

    /** @var \phpQueryObject */
    private $trPq;

    public function __construct($html)
    {
        file_put_contents(\Yii::getAlias('@common/data/page.html'), $html);
        $this->pq = \phpQuery::newDocumentHTML($html);
    }

    public function getData()
    {
        $this->trPq = $this->pq->find('#page_body tr');

        if (!$this->trPq->count()) {
            return null;
        }
        $product = new Product();
        $product->title = $this->getTitle();

        $product->storageM = (int)$this->getStorageM();

        $product->purchase = (float)$this->getPurchase();

        return $product;
    }


    private function getTitle()
    {
        return trim($this->pq->find('td:eq(1)')->text());
    }


    private function getStorageM()
    {
        return trim($this->pq->find('td:eq(3)')->text());
    }


    private function getPurchase()
    {
        return str_replace(',', '.', trim($this->pq->find('td:eq(4)')->text()));
    }

}