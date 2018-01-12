<?php


namespace console\controllers;


use arogachev\excel\import\basic\Importer;
use core\entities\Products;
use core\jobs\ParseJob;
use core\jobs\XmlJob;
use core\services\Api;
use yii\console\Controller;
use core\exceptions\RequestException;
use core\forms\LoginForm;
use core\pages\AuthPage;
use core\pages\HomePage;
use core\services\Client;


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


    public function actionXls()
    {

        $dir = \Yii::getAlias('@console/runtime/queue');
        echo count(scandir($dir)) - 2;

    }


}