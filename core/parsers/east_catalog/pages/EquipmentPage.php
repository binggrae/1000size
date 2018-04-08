<?php


namespace core\parsers\east_catalog\pages;

use core\entities\east\catalog\Equipment;
use core\entities\east\catalog\Power;
use core\parsers\Page;

class EquipmentPage extends Page
{

    public function __construct($html)
    {
        parent::__construct($html);
    }


    /**
     * @return Equipment[]|\Generator
     */
    public function getList()
    {
        foreach ($this->pq->find('.finetable tr') as $i => $item) {
            if ($i === 0) {
                continue;
            }
            $pq = pq($item);
            $name = trim($pq->find('td:eq(0)')->text());
            $href = $pq->find('td:eq(0) a')->attr('href');
            $href = explode('/', $href);
            $uid = array_pop($href);
            $year = trim($pq->find('td:eq(1)')->text());

            $pqDesc = $pq->find('td:eq(2)');
            switch ($pqDesc->find('p')->count()) {
                case 0:
                    $description = trim($pq->find('td:eq(2)')->text()) ?: '';
                    break;
                case 1:

                    $description = trim($pqDesc->find('p')->html());
                    $description = str_replace(["\r\n", "\r", "\n"], '', $description);
                    $description = explode('<br>', $description);
                    $description = array_filter($description, function($element) {
                        return $element != '';
                    });
                    $description = trim(implode(';', $description));
                    break;
                default:
                    $description = [];
                    foreach ($pqDesc->find('p') as $p) {
                        $description[] = trim(pq($p)->text());
                    }
                    $description = implode(';', $description);
            }

            yield Equipment::get($uid, $name, $year, $description);
        }
    }
}