<?php


namespace core\parsers\size\pages;


class SearchPage extends Page implements CatalogPage
{

    /** @var \phpQueryObject */
    private $pqItem;

    public $barcode;
    public $unit;
    public $storageHtml;

    public function getLink($barcode)
    {
        if (!$this->pqItem) {
            $this->loadPq();
        }

        return $this->pqItem->count() ? $this->pqItem->find('.s-catalog-groups__link')->attr('href') : null;
    }

    public function getTitle()
    {
        if (!$this->pqItem) {
            $this->loadPq();
        }

        return $this->pqItem->count() ? trim($this->pqItem->find('.s-link__inner')->text()) : null;
    }

    public function getUnit()
    {
        if (!$this->pqItem) {
            $this->loadPq();
        }
        $this->getStorageHtml();

        return $this->pqItem->count() ? $this->unit : 'шт.';
    }

    public function getStorageM()
    {
        if (!$this->pqItem) {
            $this->loadPq();
        }

        return $this->pqItem->count() ? $this->getStorageHtml()['m'] : null;
    }

    public function getStorageV()
    {
        if (!$this->pqItem) {
            $this->loadPq();
        }

        return $this->pqItem->count() ? $this->getStorageHtml()['v'] : null;
    }

    public function getPurchase()
    {
        if (!$this->pqItem) {
            $this->loadPq();
        }

        return $this->pqItem->count() ? intval($this->pqItem->find('.s-catalog-groups__price')->text()) : null;
    }

    public function getRetail()
    {
        if (!$this->pqItem) {
            $this->loadPq();
        }

        return $this->pqItem->count() ? intval(explode(':', $this->pqItem->find('.s-catalog-groups__no_sellout_price')->text())[1]) : null;
    }

    private function loadPq()
    {
        foreach ($this->pq->find('.s-catalog-groups__item') as $item) {
            $pq = pq($item);

            if ($this->barcode == trim(explode(':', $pq->find('.s-catalog-groups__item-barcode')->text())[1])) {
                $this->pqItem = $pq;
            }
        }

        if (!$this->pqItem) {
            $this->pqItem = \phpQuery::newDocumentHTML();
        }
    }

    private function getStorageHtml()
    {
        if (!$this->storageHtml) {
            $pq = $this->pqItem->find('.s-catalog-groups__availability');

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
}