<?php

namespace core\pages\east;

use core\elements\size\Root;
use core\pages\Page;

class HomePage extends Page
{
    const URL = 'http://part.m-powergroup.ru/';

    private $pq;

    public function __construct($html)
    {
//        file_put_contents(\Yii::getAlias('@common/data/login.html'), $html);
        $this->pq = \phpQuery::newDocumentHTML($html);
    }


    public function isLogin()
    {
        return !$this->pq->find('.login-form')->count();
    }
}