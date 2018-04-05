<?php


namespace core\parsers;


abstract class Page
{
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