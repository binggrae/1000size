<?php


namespace core\pages\size;



class CategoryPage extends HomePage
{


    public function __construct($html)
    {
		file_put_contents(\Yii::getAlias('@common/data/cat_' . uniqid() . '.html'), $html);
        parent::__construct($html);
    }


    public function getLinks()
    {
        $links = [];
        $list = $this->pq->find('.s-catalog-groups__item');
        foreach ($list as $item) {
            $pq = pq($item);

            yield $pq->find('.s-catalog-groups__link')->attr('href');
        }
        return $links;
    }


    public function hasNext()
    {
        $pages = $this->pq->find('.s-pager__link');
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