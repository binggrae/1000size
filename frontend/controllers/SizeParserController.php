<?php


namespace frontend\controllers;


use core\entities\size\Categories;
use core\entities\size\Products;
use yii\filters\AccessControl;
use yii\web\Controller;

class SizeParserController extends Controller
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

    public function actionCategories()
    {
        $categories = Categories::find()->all();

        return $this->render('categories', [
            'categories' => $categories
        ]);
    }

    public function actionView($id)
    {
        $products = Products::find()->where(['category_id' => $id])->all();
        return $this->render('view', [
            'products' => $products
        ]);
    }



}