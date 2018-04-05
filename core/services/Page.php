<?php


namespace core\services;


abstract class Page
{
    /** @var \phpQueryObject */
    protected $pq;

    public function __construct($html)
    {
        $this->pq = \phpQuery::newDocumentHTML($html);
    }

    public function close()
    {
        \phpQuery::unloadDocuments();
    }

}