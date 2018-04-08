<?php


namespace core\parsers\east_catalog\pages;

use core\entities\east\catalog\Power;
use core\parsers\Page;

class PowerPage extends Page
{

    public function __construct($html)
    {
        parent::__construct($html);
    }


    /**
     * @return Power[]|\Generator
     */
    public function getList()
    {
        foreach ($this->pq->find('.finetable tr') as $i => $item) {
            if ($i === 0) {
                continue;
            }
            $pq = pq($item)->find('td:eq(0)');
            $href = $pq->find('a')->attr('href');
            $href = explode('/', $href);
            yield Power::get(array_pop($href), trim($pq->text()));
        }
    }
}