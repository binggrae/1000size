<?php


namespace frontend\controllers;


use core\entities\power\Products as PowerProducts;
use core\entities\size\Products as SizeProducts;
use core\entities\size\Products;
use yii\filters\AccessControl;
use yii\web\Controller;

class DashboardController extends Controller
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
        $size = [
            'date' => date('d.m.Y H:i:s', \Yii::$app->settings->get('size.date')),
            'count' => \Yii::$app->settings->get('size.count'),
            'error' => Products::find()->where(['status' => Products::STATUS_REMOVE])->count(),
            'xml' => \Yii::$app->settings->get('size.xml'),
            'xls' => \Yii::$app->settings->get('size.xls'),
        ];

        $power = [
            'error' => \Yii::$app->settings->get('power.error'),
            'count' => \Yii::$app->settings->get('error_count', 'power', 0),
            'date' => date('d.m.Y H:i:s', \Yii::$app->settings->get('power.date')),
            'status' => \Yii::$app->settings->get('is_job', 'power', false),
            'xml' => \Yii::$app->settings->get('power.xml'),
        ];


        $techno = [
            'date' => date('d.m.Y H:i:s', \Yii::$app->settings->get('techno.date')),
            'xml' => \Yii::$app->settings->get('techno.xml'),
        ];

        $bch = [
            'date' => date('d.m.Y H:i:s', \Yii::$app->settings->get('bch.date')),
            'xml' => \Yii::$app->settings->get('bch.xml'),
            'status' => \Yii::$app->settings->get('is_job', 'bch', false),
        ];

        $east = [
            'error' => \Yii::$app->settings->get('eastmarine.error'),
            'count' => \Yii::$app->settings->get('error_count', 'eastmarine', 0),
            'date' => date('d.m.Y H:i:s', \Yii::$app->settings->get('eastmarine.date')),
            'status' => \Yii::$app->settings->get('is_job', 'eastmarine', false),
            'xml' => \Yii::$app->settings->get('eastmarine.xml'),
        ];

        $automaster = [
            'date' => date('d.m.Y H:i:s', \Yii::$app->settings->get('automaster.date')),
            'xml' => \Yii::$app->settings->get('automaster.xml'),
        ];

        return $this->render('index', [
            'techno' => $techno,
            'automaster' => $automaster,
            'size' => $size,
            'east' => $east,
            'bch' => $bch,
            'power' => $power,
        ]);
    }


}