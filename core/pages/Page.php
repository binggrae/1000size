<?php


namespace core\pages;


abstract class Page
{
    public function close()
    {
        \phpQuery::unloadDocuments();
    }
}