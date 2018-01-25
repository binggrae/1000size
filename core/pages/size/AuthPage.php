<?php

namespace core\pages\size;

class AuthPage
{
    const URL = 'http://opt.1000size.ru/signin?to=home';

    private $pq;


    public function __construct($html)
    {
        $this->pq = \phpQuery::newDocumentHTML($html);
    }
}