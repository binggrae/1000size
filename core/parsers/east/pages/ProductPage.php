<?php


namespace core\parsers\east\pages;

class ProductPage extends Page
{

    public function __construct($html)
    {
        parent::__construct($html);
    }

    /** @var \phpQueryObject */
    private $trPq;

    public function hasResult($barcode)
    {
        foreach ($this->pq->find('.catalog .details') as $item) {
            $temp = trim(pq($item)->find('.parts:eq(0)')->text());
            if ($temp == $barcode) {
                $this->trPq = pq($item);
                return true;
            }
        }
        return false;
    }

    public function getTitle()
    {
        return trim($this->trPq->find('.description')->text());
    }

    public function getStorage()
    {
        foreach ($this->trPq->find('.refuse:eq(0) .rf') as $storage) {
            if (!$storage) {
                return 0;
            }
            $text = explode(' : ', pq($storage)->text());
            if ($text[0] == 'Основной склад') {
                return (int)$text[1];
            }
        }

        return 0;
    }


    public function getPrice()
    {
        $purchase = 0;
        $retail = 0;
        foreach ($this->trPq->find('.price1:eq(0) .pr') as $price) {
            $text = explode(' : ', pq($price)->text());
            if ($text[0] == 'Оптовая') {
                $purchase = (float)$text[1];
            }
            if ($text[0] == 'Розничная') {
                $retail = (float)$text[1];
            }
        }

        return [$purchase, $retail];
    }

}