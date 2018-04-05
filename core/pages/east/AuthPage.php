<?php

namespace core\pages\east;

use core\pages\Page;

class AuthPage extends Page
{
    const URL = 'http://dealer.eastmarine.ru/login';

    private $pq;


    public function __construct($html)
    {
        $this->pq = \phpQuery::newDocumentHTML($html);
    }

    public function isLogin()
    {
        return $this->pq->find('title')->text() != 'Ошибка авторизации';
    }
}