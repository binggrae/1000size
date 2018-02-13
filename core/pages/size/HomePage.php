<?php

namespace core\pages\size;

use yii\helpers\Json;

class HomePage
{
    const URL = 'https://opt.1000size.ru/';

    protected $pq;

    public function __construct($html)
    {
        $this->pq = \phpQuery::newDocumentHTML($html);
//        file_put_contents(\Yii::getAlias('@common/data/all_'.uniqid().'.html'), $this->pq->html());
    }


    public function isLogin()
    {
//        var_dump($this->pq->find('#auth_login_form')->html());
        return !$this->pq->find('#auth_login_form')->count();
    }

    public function getToken()
    {
        return $this->pq->find('#login__csrf_token')->attr('value');
    }
}