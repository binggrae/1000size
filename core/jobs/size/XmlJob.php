<?php


namespace core\jobs\size;


use core\entities\logs\XmlLog;
use core\entities\size\Products;
use core\services\Xml;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class XmlJob extends BaseObject implements JobInterface
{

    /** @var Products[] */
    public $products;

    public $log_id;

    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        $log = XmlLog::start($this->log_id);
        try{
            $this->products = Products::find()->all();
            $xml = new Xml($this->products);
            $xml->generate();
            $xml->save('@frontend/web/' . \Yii::$app->settings->get('file.xml'));
        } catch (\Exception $e) {
            $log->error(XmlLog::CODE_PARSE_ERROR, $e->getMessage());
            throw $e;
        }
        $log->end(count($this->products));
    }


}