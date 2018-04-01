<?php


namespace console\controllers;

use core\entities\power\Products;
use core\forms\power\LoginForm;
use core\jobs\power\ParseJob;
use core\jobs\power\ProductJob;
use core\jobs\power\XlsJob;
use core\jobs\power\XmlJob;
use core\services\power\Api;
use core\services\Xml;
use core\services\XmlImport;
use yii\console\Controller;


class PowerParserController extends Controller
{

    /**
     * @var Api
     */
    private $api;

    public function __construct(string $id, $module, Api $api, array $config = [])
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
            'login' => \Yii::$app->settings->get('power.login'),
            'password' => \Yii::$app->settings->get('power.password'),
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