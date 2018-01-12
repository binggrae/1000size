<?php


namespace core\jobs;


use core\entities\Products;
use core\services\Xml;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class XmlJob extends BaseObject implements JobInterface
{

    /** @var Products[] */
    public $products;


    public function execute($queue)
    {
        $xml = new Xml($this->products);
        $xml->generate();
        $xml->save('@frontend/web/' . \Yii::$app->settings->get('file.xml'));
    }


}