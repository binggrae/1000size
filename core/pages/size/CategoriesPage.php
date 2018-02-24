<?php


namespace core\pages\size;

use yii\helpers\VarDumper;

class CategoriesPage extends HomePage
{

    public function __construct($html)
    {
        preg_match('/id="full_categories_popup_tpl">(.+)s-catalog-overlay__empty-button/usi', $html, $math);
        parent::__construct($math[1]);
    }

    public function getCategories()
    {
        $data = ['items' => []];
        foreach ($this->pq->find('li') as $item) {
            $pqItem = pq($item);
            $id = preg_replace('/cat_\w_/', '', $pqItem->attr('id'));
            $ids = explode('_', $id);
            $parent = &$data['items'];

            foreach ($ids as $index => $key) {
                if (!isset($parent[$key])) {
                    $pqLink = $pqItem->find('a:eq(0)');
                    $parent[$key] = [
                        'items' => [],
                        'id' => $id,
                        'link' => $pqLink->attr('href'),
                        'title' => $pqLink->text(),
                    ];
                }
                $parent = &$parent[$key]['items'];
            }
        }
        return $data;
    }

}