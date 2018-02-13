<?php


namespace core\jobs\size;


use core\entities\size\Categories;
use core\entities\size\Products;
use core\services\size\Api;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class ParseJob extends BaseObject implements JobInterface
{

    /**
     * @var Api
     */
    private $api;


    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        \Yii::$app->settings->set('parser.is_job', 1);

        $this->api = \Yii::$container->get(Api::class);

        $this->api->login();

        $this->api->getCategories();

        $categories = Categories::find()->where(['<', 'status', 5])->all();
        $this->api->getPages($categories);

        $products = Products::find()
            ->where(['status' => 0])
            ->indexBy('id')
            ->batch(10);

        foreach ($products as $product) {
            \Yii::$app->queue->push(new ProductJob([
                'ids' => array_keys($product)
            ]));
        }
    }
}