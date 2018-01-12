<?php


namespace frontend\controllers;


use core\entities\Products;
use core\jobs\ParseJob;
use core\services\Api;
use yii\web\Controller;
use yii\filters\AccessControl;

class ParserController extends Controller
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

		
    /**
     * @var Api
     */
    private $api;

    public function __construct(string $id, $module, Api $api, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->api = $api;
    }


    public function actionStats()
    {
        $date = \Yii::$app->settings->get('parser.date');
        $count = Products::find()->count();
        $status = \Yii::$app->settings->get('parser.is_job');


        return $this->render('stats', [
            'count' => $count,
            'date' => $date,
            'status' => $status,
        ]);
    }


    public function actionStart()
    {
        if (!\Yii::$app->settings->get('parser.is_job')) {
            \Yii::$app->queue->push(new ParseJob($this->api, [
                'login' => \Yii::$app->settings->get('parser.login'),
                'password' => \Yii::$app->settings->get('parser.password'),
            ]));
        }

        return $this->redirect('stats');
    }


}