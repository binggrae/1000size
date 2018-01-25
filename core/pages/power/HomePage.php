<?php

namespace core\pages\power;

use core\elements\size\Root;

class HomePage
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

    public function getToken()
    {
        return $this->pq->find('input[name="session_id"]')->attr('value');
    }
}