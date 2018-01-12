<?php


namespace frontend\controllers;


use core\entities\Products;
use core\jobs\ParseJob;
use core\services\Api;
use yii\web\Controller;

class ParserController extends Controller
{

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
        $xls = \Yii::getAlias('@frontend/web/' . \Yii::$app->settings->get('file.xls'));
        $xml = \Yii::getAlias('@frontend/web/' . \Yii::$app->settings->get('file.xml'));
        $status = \Yii::$app->settings->get('parser.is_job');


        return $this->render('stats', [
            'count' => $count,
            'date' => $date,
            'xls' => $xls,
            'xml' => $xml,
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