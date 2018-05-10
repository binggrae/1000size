<?php


namespace core\parsers\size;


use core\entities\Factor;
use core\entities\size\Products;
use core\exceptions\RequestException;
use core\parsers\size\elements\Product;
use core\parsers\size\forms\LoginForm;
use core\parsers\size\pages\CatalogPage;
use core\parsers\size\pages\HomePage;
use core\parsers\size\pages\ProductPage;
use core\parsers\size\pages\SearchPage;
use core\services\Client;
use core\services\exports\Xml;

class Parser
{

    const BASE_URL = 'https://opt.1000size.ru/';

    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {

        $this->client = $client;
        $this->client->setCookie('size');
    }


    public function run()
    {
        $product = Products::find()
            ->where(['!=', 'status', [Products::STATUS_REMOVE, Products::STATUS_INACTIVE]])
            ->orderBy(['load_ts' => SORT_ASC])
            ->one();

        $this->load($product);

        var_dump(date('d.m.Y H:i:s') . ' | End load');
    }

    public function save()
    {
        $factor = Factor::find()->where(['key' => '1000size'])->one();
        $factor = $factor ? $factor->value : 1.3;

        $xml = new Xml(['barcode', 'title', 'unit', 'storage', 'purchase', 'retail']);

        $products = Products::find()
            ->where(['>', 'load_ts', time() - (60 * 60 * 24 * 3)])
            ->andWhere(['status' => Products::STATUS_ACTIVE])
            ->all();

        foreach ($products as $product) {
            $xml->addProduct(new Product($product, $factor));
        }

        \Yii::$app->settings->set('size.date', time());
        \Yii::$app->settings->set('size.count', count($products));

        $xml->save('@frontend/web/' . \Yii::$app->settings->get('size.xml'));
    }

    private function load(Products &$product)
    {
        var_dump(date('d.m.Y H:i:s')  . ' | Barcode: ' . $product->barcode);
        /** @var CatalogPage $page */

        try {
            do {
                if ($product->status == Products::STATUS_NEW) {
                    $response = $this->client->get(self::BASE_URL . 'search', [
                        'q' => $product->barcode
                    ])->send();

                    $page = HomePage::create($response);
                } else {
                    $response = $this->client->get(self::BASE_URL . $product->link)->send();

                    $page = new ProductPage($response->content);
                }
                $page->barcode = $product->barcode;

                $is_login = $page->isLogin();
                if (!$is_login) {
                    $this->login();
                    $page->close();
                } else {
                    if (!$product->link) {
                        $product->link = $page->getLink($product->barcode);
                    }
                }
            } while (!$is_login);


            $product->title = $page->getTitle();
            $product->unit = $page->getUnit();
            $product->storageM = $page->getStorageM();
            $product->storageV = $page->getStorageV();
            $product->purchase = $page->getPurchase();
            $product->retail = $page->getRetail();
            $product->load_ts = time();
            $product->status = Products::STATUS_ACTIVE;
            $product->save();
        } catch (\Exception $e) {
            var_dump('Error: ' . $response->getStatusCode());
            $product->status = Products::STATUS_REMOVE;
            $product->title = 'remove';
            var_dump($product->save());
            var_dump($product->getErrors());
        }
    }

    private function login()
    {
        var_dump('login');
        $form = new LoginForm([
            'login' => \Yii::$app->settings->get('size.login'),
            'password' => \Yii::$app->settings->get('size.password'),
        ]);

        do {
            $request = $this->client->get(self::BASE_URL)->send();
            if ($request->isOk) {
                $home = new HomePage($request->content);
                if ($home->isLogin()) {
                    return true;
                }
            } else {
                throw new RequestException('Failed load login page');
            }
            $form->token = $home->getToken();
            $home->close();

            $request = $this->client->post($home->getAuthAction(), $form->getPostData())->send();
            if ($request->isOk) {
                $home = new HomePage($request->content);
                return $home->isLogin();
            } else {
                var_dump($request->getHeaders());
                throw new RequestException('Failed auth');
            }
        } while (1);
    }

}