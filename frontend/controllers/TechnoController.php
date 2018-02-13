<?php


namespace frontend\controllers;


use core\jobs\techno\ParseJob;
use yii\filters\AccessControl;
use yii\web\Controller;

class TechnoController extends Controller
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


    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionStart()
    {
        \Yii::$app->queue->push(new ParseJob());
        sleep(5);

        return $this->redirect('index');
    }




}