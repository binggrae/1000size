<?php


namespace core\jobs\size;


use core\entities\size\Products;
use core\pages\size\ProductPage;
use core\services\Client;
use core\services\size\Api;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class ProductJob extends BaseObject implements JobInterface
{
    public $ids;

    /**
     * @var Client
     */
    private $client;

    /** @var Api */
    private $api;

    private function getApi()
    {
        if (!$this->api) {
            $this->api = \Yii::$container->get(Api::class);
        }

        return $this->api;
    }

    public function execute($queue)
    {
        $this->client = \Yii::$container->get(Client::class);

        $products = Products::find()->indexBy('id')->where(['id' => $this->ids])->all();

        $is_load = true;
        do {
            if(!$is_load) {
                $this->getApi()->login();
            }

            $requests = [];
            foreach ($products as $product) {
                $requests[$product->id] = $this->client->get('https://opt.1000size.ru/' . $product->link);
            }

            $responses = $this->client->batch($requests);

            foreach ($responses as $id => $response) {
                $productPage = new ProductPage($response->content);
                if (!$productPage->isLogin()) {
                    $is_load = false;
                    continue;
                }

                $products[$id]->setAttributes(get_object_vars($productPage->getData()));
                $products[$id]->save();

                unset($products[$id]);
            }

        } while (count($products));
    }


}