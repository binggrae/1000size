<?php


namespace console\controllers;

use core\entities\east\Products;
use core\forms\east\LoginForm;
use core\jobs\east\ParseJob;
use core\jobs\east\XmlJob;
use core\services\east\Api;
use core\services\XmlImport;
use yii\console\Controller;


class EastController extends Controller
{

    /**
     * @var Api
     */
    private $api;

    public function __construct(string $id, $module, Api $api,  array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->api = $api;
    }


    /**
     * @throws \Exception
     */
    public function actionRun()
    {
        \Yii::$app->queue->push(new ParseJob([
            'login' => \Yii::$app->settings->get('eastmarine.login'),
            'password' => \Yii::$app->settings->get('eastmarine.password'),
        ]));
    }


    public function actionSave()
    {
        \Yii::$app->queue->priority(2000)->push(new XmlJob());
    }


    public function actionXls()
    {
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
    }


}