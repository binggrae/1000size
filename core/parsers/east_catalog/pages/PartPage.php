<?php


namespace core\parsers\east_catalog\pages;

use core\entities\east\catalog\Part;
use core\parsers\Page;

class PartPage extends Page
{

    public function __construct($html)
    {
        parent::__construct($html);
    }


    /**
     * @return Part[]|\Generator
     */
    public function getList()
    {
        foreach ($this->pq->find('.finetable tr') as $i => $item) {
            if ($i === 0) {
                continue;
            }
            $pq = pq($item);
            $uid = trim($pq->find('.n_partnumber')->text());
            $name = trim($pq->find('td:eq(3)')->text());
            $quantity = trim($pq->find('td:eq(6)')->text());

            yield Part::get($uid, $name, $quantity);
        }
    }
}