<?php


namespace console\controllers;


use core\parsers\bch\Api;
use yii\console\Controller;

class BchController extends Controller
{

    /** @var Api */
    private $api;

    public function __construct(string $id, $module, Api $api, array $config = [])
    {
        $this->api = $api;
        parent::__construct($id, $module, $config);
    }


    public function actionParse()
    {
        $this->api->parse();
    }


}