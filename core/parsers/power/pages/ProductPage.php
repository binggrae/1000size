<?php


namespace core\parsers\power\pages;

class ProductPage extends Page
{

    public function __construct($html)
    {
        $posStart = strpos($html, '<div class="wrapper1');
        $posEnd = strpos($html, '<footer');
        $html = substr($html, $posStart, $posEnd - $posStart);

        parent::__construct($html);
    }

    /** @var \phpQueryObject */
    private $trPq;

    public function hasResult($barcode)
    {
        foreach ($this->pq->find('#page_body tr') as $item) {
            if (trim(pq($item)->find('td:eq(0)')->text()) == $barcode) {
                $this->trPq = pq($item);
                return true;
            }
        }
        return false;
    }

    public function getTitle()
    {
        return trim($this->trPq->find('td:eq(1)')->text());
    }

    public function getStorages()
    {
        return (integer)trim($this->trPq->find('td:eq(3)')->text());
    }


    public function getPurchase()
    {
        return (float)str_replace(',', '.', trim($this->trPq->find('td:eq(4)')->text()));
    }

}