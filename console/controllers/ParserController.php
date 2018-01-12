<?php


namespace console\controllers;


use arogachev\excel\import\basic\Importer;
use core\entities\Products;
use core\jobs\ParseJob;
use core\jobs\XmlJob;
use core\services\Api;
use yii\console\Controller;
use core\exceptions\RequestException;
use core\forms\LoginForm;
use core\pages\AuthPage;
use core\pages\HomePage;
use core\services\Client;


class ParserController extends Controller
{

    /**
     * @var Api
     */
    private $api;

    public function __construct(string $id, $module, Api $api, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->api = $api;
    }


    public function actionRun()
    {
        \Yii::$app->queue->push(new ParseJob($this->api, [
            'login' => \Yii::$app->settings->get('parser.login'),
            'password' => \Yii::$app->settings->get('parser.password'),
        ]));
    }


    public function actionSave()
    {
        \Yii::$app->queue->push(new XmlJob([
            'products' => Products::find()->all()
        ]));

    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionXls()
    {
		if (\Yii::$app->settings->get('parser.is_job')) {
            //return;
        }

        \Yii::$app->settings->set('parser.is_job', 1);

        $form = new LoginForm([
			'login' => \Yii::$app->settings->get('parser.login'),
            'password' => \Yii::$app->settings->get('parser.password'),
        ]);

        Products::deleteAll();

        if ($this->api->login($form)) {
            $categories = $this->api->getCategories();
            foreach ($categories as $category) {
                $page = 1;
                do {
                    $productsList = $this->api->getProducts($category, $page);
                    foreach ($productsList->list as $item) {
                        $product = new Products(get_object_vars($item));
                        $product->save();
                    }
                    $page++;
                } while ($productsList->hasNext);
            }
        } else {
			var_dump('sdfsdf');
		}

        $products = Products::find()->all();

        \Yii::$app->queue->push(new XmlJob([
            'products' => $products
        ]));

        \Yii::$app->queue->push(new XlsJob());
        \Yii::$app->settings->set('parser.date', time());

        \Yii::$app->settings->set('parser.is_job', 0);

    }


}