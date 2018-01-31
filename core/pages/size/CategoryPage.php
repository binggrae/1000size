<?php


namespace core\pages\size;



class CategoryPage
{

    private $pq;

    public function __construct($html)
    {
        $this->pq = \phpQuery::newDocumentHTML($html);
    }

    public function getLinks()
    {
        $links = [];
        $list = $this->pq->find('.s-catalog-groups__item');
        foreach ($list as $item) {
            $pq = pq($item);

            $links[] = $pq->find('.s-catalog-groups__link')->attr('href');
        }
        return $links;

    }


    public function hasNext()
    {
        $pages = $this->pq->find('.s-pager__link');
        var_dump($pages->text());
        foreach ($pages as $page) {
            $pq = pq($page);
            $last = $this->pq->find('.s-pager__item_current_yes')->text();
            $count = strlen($last);

            if(
                $pq->find('.s-link__inner')->text() == 'сюда' &&
                substr($pq->attr('href'), -$count) != $last
            ) {
                return true;
            }

        }
        return false;
    }

}