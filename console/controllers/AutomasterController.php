<?php


namespace console\controllers;


use core\entities\automaster\Product;
use core\services\Xml;
use core\jobs\automaster\ParseJob;
use core\services\XmlImport;
use yii\console\Controller;

class AutomasterController extends Controller
{

    public function actionTest()
    {
        $job = new ParseJob();
        $job->execute(null);
    }


    public function actionRun()
    {
        \Yii::$app->queue->push(new ParseJob());
    }
}