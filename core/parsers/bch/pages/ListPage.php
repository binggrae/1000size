<?php


namespace core\parsers\bch\pages;


use core\parsers\bch\elements\Category;
use core\parsers\bch\elements\Product;
use core\parsers\Page;

class ListPage extends Page
{

    public function __construct($html)
    {
        parent::__construct($html);
    }

    public function getList()
    {
        $products = [];
        foreach ($this->pq->find('#cat3') as $item) {
            $pq = pq($item);

            $products[] = new Product($pq->find(' > a:eq(0)')->attr('href'));
        }

        return $products;
    }

    public function isParent()
    {
        return $this->pq->find('#cat2')->count() > 0;
    }

    public function getChildren()
    {
        foreach ($this->pq->find('#cat2 h4 a') as $item)
        {
            yield new Category(pq($item)->attr('href'));
        }
    }


}