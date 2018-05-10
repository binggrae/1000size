<?php


namespace core\parsers\size\pages;


use yii\httpclient\Response;

class Page extends \core\parsers\Page
{

    public function __construct($html)
    {
        $this->save('login.html', $html);
        parent::__construct($html);
    }

    public function isLogin()
    {
        return !$this->pq->find('#auth_login_form')->count();
    }

}