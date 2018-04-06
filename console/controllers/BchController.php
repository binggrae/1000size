<?php


namespace console\controllers;


use core\jobs\BchParseJob;
use core\parsers\bch\Api;
use yii\console\Controller;

class BchController extends Controller
{

    public function actionParse()
    {
        \Yii::$app->queue->push(new BchParseJob());
    }


}