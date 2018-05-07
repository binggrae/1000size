<?php


namespace core\parsers\east\pages;

use \core\parsers\Page as BasePage;

class Page extends BasePage
{

    public function isAuth()
    {
        return !$this->pq->find('form[name="authform"]')->count();
    }
}