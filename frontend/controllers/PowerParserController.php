<?php


namespace frontend\controllers;


use core\entities\power\Products;
use core\forms\power\LoadForm;
use core\jobs\power\ParseJob;
use core\services\power\Importer;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

class PowerParserController extends Controller
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
        $imported = Products::find()->where(['status' => Products::STATUS_NEW])->count();
        $loaded = Products::find()->where(['status' => Products::STATUS_LOADED])->count();
        $removed = Products::find()->where(['status' => Products::STATUS_REMOVED])->count();
        $job = Products::find()->where(['status' => Products::STATUS_IN_JOB])->count();

        $model = new LoadForm();

        return $this->render('index', [
            'imported' => $imported,
            'loaded' => $loaded,
            'removed' => $removed,
            'job' => $job,
            'model' => $model
        ]);
    }

    public function actionList($status)
    {
        $products = Products::find()->where([ 'status' => $status])->all();
        return $this->render('view', [
            'products' => $products
        ]);
    }

    public function actionLoad()
    {
        $model = new LoadForm();

        if(\Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            try{
                $importer = new Importer($model->file->tempName);
                $importer->import();

                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $model->addError('file', 'Ошибка загрузки');
            }
        }

        return $this->render('import', [
            'model' => $model
        ]);
    }

    public function actionStart()
    {
        \Yii::$app->queue->push(new ParseJob([
            'login' => \Yii::$app->settings->get('power.login'),
            'password' => \Yii::$app->settings->get('power.password'),
        ]));

        sleep(5);

        return $this->redirect('index');
    }


}