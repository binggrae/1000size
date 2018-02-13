<?php


namespace core\jobs\size;


use core\entities\logs\XlsLog;
use core\entities\size\Products;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class XlsJob extends BaseObject implements JobInterface
{

    public $log_id;

    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        $log = XlsLog::start($this->log_id);
        try{
            $file = \Yii::createObject([
                'class' => 'codemix\excelexport\ExcelFile',
                'sheets' => [
                    'ЦеныИОстатки' => [
                        'class' => 'codemix\excelexport\ActiveExcelSheet',
                        'query' => Products::find()->where(['status' => 0]),
                        'attributes' => ['barcode', 'title', 'unit', 'storageM', 'storageV', 'purchase', 'retail', 'brand', 'country'],
                    ]
                ]
            ]);
            $file->saveAs(\Yii::getAlias('@frontend/web/' . \Yii::$app->settings->get('file.xls')));
        } catch (\Exception $e) {
            $log->error(XlsLog::CODE_PARSE_ERROR, $e->getMessage());
            throw $e;
        }
        $log->end(Products::find()->count());
    }


}