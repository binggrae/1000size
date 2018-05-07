<?php


namespace core\parsers\bch\pages;


use core\parsers\bch\Api;
use core\parsers\bch\elements\Storage;
use core\parsers\Page;

class ItemPage extends Page
{

    public function __construct($html)
    {
        parent::__construct($html);
        file_put_contents(\Yii::getAlias('@common/data/view.html'), $html);
    }

    private $barcode;

    public function getBarcode()
    {
        return $this->barcode = explode('. ', $this->pq->find('#razdel1')->text())[0];
    }

    public function getTitle()
    {
        $title = explode($this->barcode, $this->pq->find('#razdel1')->text())[1];
        return trim(substr($title, 1));
    }

    public function getPurchase()
    {
        return (integer)trim(str_replace('р.', '', $this->pq->find('#descr .button2')->text()));
    }

    public function getRetail()
    {
        $h3 = explode(':', $this->pq->find('#descr h3')->text());
        $retail = count($h3) > 1 ? $h3[1] : '';
        return (integer)trim(str_replace('р.', '', $retail));
    }

    public function getStorages()
    {
        $result = [];
        $items = $this->pq->find('#descr small u');
        foreach ($items as $item) {
            $pq = pq($item);
            $type = str_replace('В наличии', '', $pq->find('a')->text());
            $type = trim($type);
            $result[] = new Storage($type ?: 'Под заказ', (integer)$pq->find('b')->text());
        }

        return $result;
    }
}