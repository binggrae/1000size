<?php

namespace core\pages\power;

class AuthPage
{
    const URL = 'http://part.m-powergroup.ru/auth_check_user.html';

    private $pq;


    public function __construct($html)
    {
        $this->pq = \phpQuery::newDocumentHTML($html);
    }
}