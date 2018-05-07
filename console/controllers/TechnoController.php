<?php


namespace console\controllers;

use core\jobs\techno\ParseJob;
use yii\console\Controller;

class TechnoController extends Controller
{

    public function actionTest()
    {
        $job = new ParseJob();
        $job->execute(null);
    }

    public function actionRun()
    {
        \Yii::$app->settings->set('techno.is_job', true);
        \Yii::$app->queue->push(new ParseJob());
    }
}