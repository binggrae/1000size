<?php


namespace core\pages\size;


use core\elements\size\Root;

class CategoriesPage
{
    const URL = 'http://opt.1000size.ru/categories';

    private $pq;

    public function __construct($html)
    {
        $this->pq = \phpQuery::newDocumentHTML($html);
    }

    public function getList()
    {
        $categories = [];
        $elements = $this->pq->find('#catalog_side_menu .s-menu__link');

        foreach ($elements as $element)
        {
            $pq = pq($element);
            $categories[] = new Root($pq->attr('href'), trim($pq->text()));
        }

        return $categories;
    }

}