<?php


namespace console\controllers;

use core\jobs\techno\ParseJob;
use yii\console\Controller;

class TechnoController extends Controller
{

    public function actionRun()
    {
        \Yii::$app->queue->push(new ParseJob());

    }
}