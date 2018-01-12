<?php


namespace console\controllers;


use arogachev\excel\import\basic\Importer;
use core\entities\Products;
use core\jobs\ParseJob;
use core\jobs\XmlJob;
use core\services\Api;
use yii\console\Controller;

class ParserController extends Controller
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


    public function actionRun()
    {
        \Yii::$app->queue->push(new ParseJob($this->api, [
            'login' => \Yii::$app->settings->get('parser.login'),
            'password' => \Yii::$app->settings->get('parser.password'),
        ]));
    }


    public function actionSave()
    {
        \Yii::$app->queue->push(new XmlJob([
            'products' => Products::find()->all()
        ]));

    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionXls()
    {

    }


}