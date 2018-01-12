<?php


namespace core\jobs;


use core\entities\Products;
use core\forms\LoginForm;
use core\services\Api;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class ParseJob extends BaseObject implements JobInterface
{

    public $login;

    public $password;

    /**
     * @var Api
     */
    private $api;

    public function __construct(Api $api, array $config = [])
    {
        parent::__construct($config);
        $this->api = $api;
    }

    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        if (\Yii::$app->settings->get('parser.is_job')) {
            return;
        }

        \Yii::$app->settings->set('parser.is_job', 1);

        $form = new LoginForm([
            'login' => $this->login,
            'password' => $this->password
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