<?php


namespace core\actions\power;

use core\entities\power\Products;
use core\jobs\power\ProductJob;
use core\services\Client;

class ProductAction
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function run()
    {
        Products::updateAll(['status' => Products::STATUS_NEW]);
        do {
            /** @var Products[] $products */
            $products = Products::find()
                ->andWhere(['status' => Products::STATUS_NEW])
                ->limit(\Yii::$app->settings->get('power.limit'))
                ->all();
            if (!$products) {
                break;
            }

            $requests = [];
            foreach ($products as $product) {
                $product->status = Products::STATUS_IN_JOB;
                $product->save();

                $requests[$product->id] = $product->barcode;
            }

            \Yii::$app->queue->push(new ProductJob([
                'requests' => $requests,
            ]));

        } while (true);

        return true;
    }


}