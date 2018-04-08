<?php


namespace core\parsers\east_catalog\pages;

use core\entities\east\catalog\Equipment;
use core\entities\east\catalog\Power;
use core\entities\east\catalog\Unit;
use core\parsers\Page;

class UnitPage extends Page
{

    public function __construct($html)
    {
        parent::__construct($html);
    }


    /**
     * @return Unit[]|\Generator
     */
    public function getList()
    {
        foreach ($this->pq->find('.products a') as $item) {

            $pq = pq($item);
            $name = trim($pq->text());
            $href = $pq->attr('href');
            $href = explode('/', $href);
            $uid = array_pop($href);

            yield Unit::get($uid, $name);
        }
    }
}