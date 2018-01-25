<?php


namespace core\jobs\power;

use core\entities\power\Products;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class XlsJob extends BaseObject implements JobInterface
{


    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        try{
            $file = \Yii::createObject([
                'class' => 'codemix\excelexport\ExcelFile',
                'sheets' => [
                    'ЦеныИОстатки' => [
                        'class' => 'codemix\excelexport\ActiveExcelSheet',
                        'query' => Products::find()->where(['!=', 'status', Products::STATUS_REMOVED]),
                        'attributes' => ['barcodeVal', 'titleVal', 'unitVal', 'storageMVal', 'purchaseVal', 'retailVal'],
                    ]
                ]
            ]);
            $file->saveAs(\Yii::getAlias('@frontend/web/' . \Yii::$app->settings->get('power.xls')));
        } catch (\Exception $e) {
            throw $e;
        }
    }


}