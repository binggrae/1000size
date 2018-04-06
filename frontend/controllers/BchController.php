<?php


namespace frontend\controllers;


use core\jobs\BchParseJob;
use yii\web\Controller;

class BchController extends Controller
{

    public function actionStart()
    {
        \Yii::$app->queue->push(new BchParseJob());

        sleep(5);

        return $this->redirect('/dashboard/index');
    }


}