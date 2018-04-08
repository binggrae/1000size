<?php


namespace console\controllers;

use core\jobs\PowerParseJob;
use yii\console\Controller;


class PowerController extends Controller
{

    public function actionRun()
    {
        \Yii::$app->queue->push(new PowerParseJob());
    }

}