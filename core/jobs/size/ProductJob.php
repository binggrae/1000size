<?php


namespace core\jobs\size;


use core\entities\size\Products;
use core\pages\size\ProductPage;
use core\services\Client;
use core\services\size\Api;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\httpclient\Response;
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
        $chunks = array_chunk(ArrayHelper::map($products, 'id', 'link'), 2, true);

        $is_load = true;
        do {
            if (!$is_load) {
                $this->getApi()->login();
            }

            foreach ($chunks as $i => $chunk) {
                $requests = [];
                foreach ($chunk as $id => $link) {
                    $requests[$id] = $this->client->get('https://opt.1000size.ru/' . $link);
                }
                /** @var Response[] $responses */
                $responses = $this->client->batch($requests);

                foreach ($responses as $id => $response) {
                    var_dump($chunk[$id] . ': ' . $response->getStatusCode());
                    if ($response->getStatusCode() == 429) {
                        var_dump('SLEEP');
                        continue;
                    }

                    $productPage = new ProductPage($response->content);
                    if (!$productPage->isLogin()) {
                        $is_load = false;
                        continue;
                    }

                    if($products[$id]) {
                        try {
                            $products[$id]->setAttributes(get_object_vars($productPage->getData()));
                            $products[$id]->save();

                            unset($products[$id]);
                        } catch (\Exception $e) {
                            var_dump($productPage->getData());
                        }
                    }
                }
                sleep(60);
            }
        } while (count($products));
    }
}