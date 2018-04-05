<?php


namespace frontend\controllers;


use core\entities\power\Products as PowerProducts;
use core\entities\size\Products as SizeProducts;
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
            'date' => date('d.m.Y H:i:s', \Yii::$app->settings->get('parser.date')),
            'count' => SizeProducts::find()->where(['status' => 0])->count(),
            'status' => \Yii::$app->settings->get('parser.is_job'),
            'xml' => \Yii::$app->settings->get('file.xml'),
            'xls' => \Yii::$app->settings->get('file.xls'),
        ];

        $power = [
            'imported' => PowerProducts::find()->where(['status' => PowerProducts::STATUS_NEW])->count(),
            'loaded' => PowerProducts::find()->where(['status' => PowerProducts::STATUS_LOADED])->count(),
            'removed' => PowerProducts::find()->where(['status' => PowerProducts::STATUS_REMOVED])->count(),
            'job' => PowerProducts::find()->where(['status' => PowerProducts::STATUS_IN_JOB])->count(),
            'xml' => \Yii::$app->settings->get('power.xml'),
            'xls' => \Yii::$app->settings->get('power.xls'),
        ];


        $techno = [
            'date' => date('d.m.Y H:i:s', \Yii::$app->settings->get('techno.date')),
            'xml' => \Yii::$app->settings->get('techno.xml'),
        ];

        $bch = [
            'date' => date('d.m.Y H:i:s', \Yii::$app->settings->get('bch.date')),
            'xml' => \Yii::$app->settings->get('bch.xml'),
        ];

        $east = [
            'error' => \Yii::$app->settings->get('eastmarine.error'),
            'count' => \Yii::$app->settings->get('error_count', 'eastmarine', 0),
            'date' => date('d.m.Y H:i:s', \Yii::$app->settings->get('eastmarine.date')),
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