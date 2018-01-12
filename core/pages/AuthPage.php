<?php

namespace core\pages;

class AuthPage
{
    const URL = 'http://opt.1000size.ru/signin?to=home';

    private $pq;


    public function __construct($html)
    {
        $this->pq = \phpQuery::newDocumentHTML($html);
    }
}