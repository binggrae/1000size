<?php


namespace core\parsers\power\pages;

use \core\parsers\Page as BasePage;

class Page extends BasePage
{

    public function isAuth()
    {
        return !$this->pq->find('#avtorization-form')->count();
    }
}