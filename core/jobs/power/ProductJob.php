<?php


namespace core\jobs\power;

use core\entities\power\Products;
use core\pages\power\ProductPage;
use core\services\Client;
use yii\base\BaseObject;
use yii\httpclient\Response;
use yii\queue\JobInterface;

class ProductJob extends BaseObject implements JobInterface
{

    /**
     * @var Client
     */
    private $client;

    public $requests;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        $this->client = \Yii::$container->get(Client::class);

        $requests = [];
        foreach ($this->requests as $id => $barcode) {
            $requests[$id] = $this->client->post(ProductPage::URL, [
                'art' => $barcode
            ]);
        }

        /** @var Response[] $responses */
        $responses = $this->client->batch($requests);

        foreach ($responses as $id => $response) {
            if ($response->isOk) {
                $page = new ProductPage($response->content);
                if (!($data = $page->getData())) {
                    Products::updateAll(['status' => Products::STATUS_REMOVED], ['id' => $id]);
                    continue;
                }
                Products::updateAll(get_object_vars($data), ['id' => $id]);
            } else {
                Products::updateAll(['status' => Products::STATUS_REMOVED], ['id' => $id]);
            }
        }
    }


}