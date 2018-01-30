<?php

namespace core\pages\size;

use core\elements\size\Root;

class HomePage
{
    const URL = 'https://opt.1000size.ru/';

    private $pq;

    public function __construct($html)
    {
        $this->pq = \phpQuery::newDocumentHTML($html);
    }


    public function isLogin()
    {
        return !$this->pq->find('#auth_login_form')->count();
    }

    public function getToken()
    {
        return $this->pq->find('#login__csrf_token')->attr('value');
    }

    /**
     * @return Root[]
     */
    public function getRoots()
    {
        $roots = [];
        $elements = $this->pq->find('.s-catalog-groups__link');

        foreach ($elements as $element) {
            $root = pq($element);
            $roots[] = new Root($root->attr('href'), trim($root->text()));
        }

        return $roots;
    }

}