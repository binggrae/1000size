<?php


namespace console\controllers;

use core\jobs\size\ParseJob;
use core\jobs\size\XmlJob;
use core\jobs\size\XlsJob;
use core\services\size\Api;
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
        \Yii::$app->queue->priority(1000)->push(new XmlJob());
        \Yii::$app->queue->priority(1000)->push(new XmlJob());
    }

}