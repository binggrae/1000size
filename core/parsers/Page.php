<?php


namespace core\parsers;


abstract class Page
{
    /** @var \phpQueryObject */
    protected $pq;

    public function __construct($html)
    {
//        file_put_contents(\Yii::getAlias('@common/data/' . uniqid() . '.html'), $html);
        $this->pq = \phpQuery::newDocumentHTML($html);
    }

    public function close()
    {
        \phpQuery::unloadDocuments($this->pq->documentID);
    }

    protected function save($path, $html)
    {
        file_put_contents(\Yii::getAlias("@common/data/{$path}"), $html);
    }

}