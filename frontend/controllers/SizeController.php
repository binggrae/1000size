<?php


namespace frontend\controllers;


use core\entities\ProductsSearch;
use core\entities\size\Categories;
use core\entities\size\Products;
use core\jobs\size\ParseJob;
use core\parsers\size\Parser;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SizeController extends Controller
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
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionError()
    {
        $result = '';
        $products = Products::find()->where(['status' => Products::STATUS_REMOVE])->all();
        foreach ($products as $product) {
            $result .= $product->barcode . "\n";
        }
        \Yii::$app->response->format = Response::FORMAT_RAW;
        \Yii::$app->response->getHeaders()->set('Content-Type', 'text/plain');

        return $result;
    }

    public function actionSave()
    {
        $parser = \Yii::$container->get(Parser::class);
        $parser->save();

        return $this->redirect('/dashboard/index');
    }

}