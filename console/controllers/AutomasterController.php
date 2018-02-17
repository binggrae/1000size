<?php


namespace console\controllers;

use core\jobs\automaster\ParseJob;
use yii\console\Controller;

class AutomasterController extends Controller
{

    public function actionRun()
    {
        \Yii::$app->queue->push(new ParseJob());
    }
}