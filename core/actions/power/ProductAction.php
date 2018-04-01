<?php


namespace core\actions\power;

use core\entities\power\Products;
use core\jobs\power\ProductJob;
use core\services\Client;
use core\services\XmlImport;

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
        $xml = new XmlImport(\Yii::$app->settings->get('power.list'));
        $list = $xml->getList();

        $table = Products::tableName();
        \Yii::$app->db->createCommand("UPDATE {$table} SET status = :status WHERE barcode not in (" . "'" . implode("','", $list) . "'" . ")", [
            ':status' => Products::STATUS_REMOVED,
        ])->execute();

        $products = [];
        foreach ($list as $item) {
            $model = Products::find()->where(['barcode' => $item])->one();
            if (!$model) {
                $model = Products::create($item);
            }
            $model->status = Products::STATUS_IN_JOB;
            $model->save();

            $products[$model->id] = $model->barcode;
        }

        $chunks = array_chunk($products, \Yii::$app->settings->get('power.limit'), true);
        foreach ($chunks as $chunk) {
            \Yii::$app->queue->push(new ProductJob([
                'requests' => $chunk,
            ]));
        }

        return true;
    }


}