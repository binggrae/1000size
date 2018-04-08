<?php


namespace console\controllers;

use core\jobs\PowerParseJob;
use core\parsers\power\Parser;
use yii\console\Controller;


class PowerController extends Controller
{


    public function actionTest()
    {

        \Yii::$app->settings->set('power.is_job', true);
        /** @var Parser $parser */
        $parser = \Yii::$container->get(Parser::class);

        $parser->run();

    }


    public function actionRun()
    {
        \Yii::$app->queue->push(new PowerParseJob());
    }

}