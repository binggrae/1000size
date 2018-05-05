<?php


namespace core\parsers\power;


use core\parsers\power\elements\Product;
use core\parsers\power\forms\LoginForm;
use core\parsers\power\pages\LoginPage;
use core\parsers\power\pages\ProductPage;
use core\services\Client;
use core\services\exports\Xml;
use core\services\XmlImport;

class Parser
{

    const URL = 'https://m-powergroup.ru/';

    /** @var Client */
    private $client;


    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run()
    {
        \Yii::$app->settings->set('power.is_job', true);
        $this->auth();

        $import = new XmlImport(\Yii::$app->settings->get('power.list'));
        $list = $import->getList();

        $chunks = array_chunk($list, 7, 1);
        $count = count($chunks);
        $errors = [];

        $xml = new Xml(['barcode', 'title', 'unit', 'storages', 'purchase', 'retail']);
        foreach ($chunks as $k => $chunk) {
            var_dump("Load chunk {$k} of {$count}");

            /** @var Product[] $products */
            $products = [];
            foreach ($chunk as $i => $barcode) {
                $products[$i] = new Product($barcode);
            }

            $responses = $this->getResponses($products);

            foreach ($responses as $i => $response) {
                if (!$response->isOk) {
                    $errors[] = $products[$i]->barcode->value;
                    var_dump($response->getStatusCode());
                    continue;
                }
                $page = new ProductPage($response->content);
                if (!$page->hasResult($products[$i]->barcode->value)) {
                    $errors[] = $products[$i]->barcode->value;
                    $products[$i]->barcode->value = null;
                    var_dump('no result');
					$page->close();
                    continue;
                }

                $products[$i]->title->set($page->getTitle());
                $products[$i]->storages->set($page->getStorages());
                $products[$i]->purchase->set($page->getPurchase());

                $xml->addProduct($products[$i]);
                $page->close();
            }
        }

        file_put_contents(\Yii::getAlias('@frontend/web/' . \Yii::$app->settings->get('power.error')), implode("\n", $errors));
        \Yii::$app->settings->set('power.date', time());
        \Yii::$app->settings->set('power.error_count', count($errors));

        $xml->save('@frontend/web/' . \Yii::$app->settings->get('power.xml'));
        \Yii::$app->settings->set('power.date', time());
        \Yii::$app->settings->set('power.is_job', false);

    }

    /**
     * @param Product[] $products
     * @return \yii\httpclient\Response[]
     */
    private function getResponses($products)
    {
        $requests = [];
        foreach ($products as $i => $product) {
            $requests[$i] = $this->client->post(self::URL . 'profile/parts/cart/add_item', [
                'art' => $product->barcode->value
            ]);
        }
        return  $this->client->batch($requests);
    }


    private function auth()
    {
        $form = new LoginForm([
            'login' => \Yii::$app->settings->get('power.login'),
            'password' => \Yii::$app->settings->get('power.password'),
        ]);

        $response = $this->client->post($form->action, $form->data)->send();
        $page = new LoginPage($response->content);
        $isAuth = $page->isAuth();
        $page->close();

        return $isAuth;
    }


}