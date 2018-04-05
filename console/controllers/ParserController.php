<?php


namespace console\controllers;

use core\entities\size\Categories;
use core\entities\size\Products;
use core\jobs\size\ParseJob;
use core\jobs\size\ProductJob;
use core\services\size\Api;
use yii\console\Controller;


class ParserController extends Controller
{


    public function actionRun()
    {
        \Yii::$app->queue->push(new ParseJob());

    }

}