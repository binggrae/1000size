<?php


namespace frontend\controllers;


use core\entities\size\Categories;
use core\entities\size\Products;
use core\jobs\size\ParseJob;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
        $category = Categories::find()->where(['id' => $id])->one();
        if (!$category) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $products = Products::find()->where(['category_id' => $id])->all();
        return $this->render('view', [
            'products' => $products,
            'category' => $category
        ]);
    }

    public function actionStart()
    {
        if (!\Yii::$app->settings->get('parser.is_job')) {
            \Yii::$app->queue->push(new ParseJob());
        }

        return $this->redirect('/dashboard/index');
    }


}