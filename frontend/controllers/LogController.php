<?php


namespace frontend\controllers;


use core\entities\logs\Log;
use core\entities\logs\ParserLog;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class LogController extends Controller
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

    public function actionIndex()
    {
        $model = ParserLog::find()->orderBy(['id' => SORT_DESC])->all();

        return $this->render('index', [
            'model' => $model
        ]);
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $parent = ParserLog::find()->where(['id' => $id])->one();
        if(!$parent) {
            throw new NotFoundHttpException('Log not found');
        }
        $model = Log::find()
            ->andWhere(['parent_id' => $id])
            ->all();

        return $this->render('view', [
            'model' => $model,
            'parent' => $parent
        ]);

    }


}