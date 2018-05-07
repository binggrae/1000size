<?php


namespace console\controllers;


use core\jobs\east\ParseJob;
use core\jobs\EastParseJob;
use core\parsers\east\Parser;
use core\parsers\east_catalog\Parser as CatalogParser;
use yii\console\Controller;


class EastController extends Controller
{

    public function actionTest()
    {
        \Yii::$app->settings->set('eastmarine.is_job', true);
        /** @var Parser $parser */
        $parser = \Yii::$container->get(Parser::class);

        $parser->run();
    }


    public function actionRun()
    {
        \Yii::$app->queue->push(new EastParseJob());
    }


    public function actionCatalog()
    {
        $parser = \Yii::$container->get(CatalogParser::class);
        $parser->run();
    }


}