<?php


namespace frontend\controllers;


use core\jobs\east\ParseJob;
use yii\filters\AccessControl;
use yii\web\Controller;

class EastController extends Controller
{

    /**
     * @inheritdoc
     */
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
        \Yii::$app->queue->push(new ParseJob([
            'login' => \Yii::$app->settings->get('east.login'),
            'password' => \Yii::$app->settings->get('east.password'),
        ]));

        sleep(5);

        return $this->redirect('/dashboard/index');
    }


}