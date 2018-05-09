<?php


namespace frontend\controllers;

use core\jobs\EastParseJob;
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
        \Yii::$app->settings->set('east.is_job', true);

        \Yii::$app->queue->push(new EastParseJob());

        sleep(5);

        return $this->redirect('/dashboard/index');
    }


}