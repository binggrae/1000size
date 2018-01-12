<?php


namespace core\elements;


class ProductList
{
    /**
     * @var Product[]
     */
    public $list;
    public $hasNext;

    public function __construct($list, $hasNext)
    {
        $this->list = $list;
        $this->hasNext = $hasNext;
    }


}