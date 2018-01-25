<?php


namespace core\jobs\power;


use core\entities\power\Products;
use core\services\Xml;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class XmlJob extends BaseObject implements JobInterface
{

    /** @var Products[] */
    public $products;


    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        try{
            $this->products = Products::find()->where(['!=', 'status', Products::STATUS_REMOVED])->all();
            $xml = new Xml($this->products);
            $xml->generate();
            $xml->save('@frontend/web/' . \Yii::$app->settings->get('power.xml'));
        } catch (\Exception $e) {
            throw $e;
        }
    }


}