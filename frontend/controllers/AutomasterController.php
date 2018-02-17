<?php


namespace frontend\controllers;


use core\jobs\automaster\ParseJob;
use yii\filters\AccessControl;
use yii\web\Controller;

class AutomasterController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionStart()
    {
        \Yii::$app->queue->push(new ParseJob());
        sleep(5);

        return $this->redirect('/dashboard/index');

    }


}