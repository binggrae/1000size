<?php


namespace core\jobs;


use core\entities\Products;
use core\services\Xml;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class XlsJob extends BaseObject implements JobInterface
{


    /**
     * @param \yii\queue\Queue $queue
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'sheets' => [
                'ЦеныИОстатки' => [
                    'class' => 'codemix\excelexport\ActiveExcelSheet',
                    'query' => Products::find(),
                    'attributes' => ['barcode', 'title', 'unit', 'storageM', 'storageV', 'purchase', 'retail', 'brand', 'country'],
                ]
            ]
        ]);
        $file->saveAs(\Yii::getAlias('@frontend/web/' . \Yii::$app->settings->get('file.xls')));
    }


}