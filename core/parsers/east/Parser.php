<?php


namespace core\parsers\east;


use core\entities\Factor;
use core\parsers\east\elements\Product;
use core\parsers\east\forms\LoginForm;
use core\parsers\east\pages\LoginPage;
use core\parsers\east\pages\ProductPage;
use core\services\Client;
use core\services\exports\Xml;
use core\services\XmlImport;

class Parser
{

    const URL = 'http://dealer.eastmarine.ru/';

    /** @var Client */
    private $client;


    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->client->setCookie('east');
    }

    public function run()
    {
        \Yii::$app->settings->set('eastmarine.is_job', true);
        var_dump($this->auth());

        $factor = Factor::find()->where(['key' => 'eastmarine'])->one();
        $factor = $factor ? $factor->value : 1.3;

        $import = new XmlImport(\Yii::$app->settings->get('eastmarine.list'));
        $list = $import->getList();

        $chunks = array_chunk($list, 1, 1);
        $count = count($chunks);
        $errors = [];

        $xml = new Xml(['barcode', 'title', 'unit', 'storage', 'purchase', 'retail']);
        foreach ($chunks as $k => $chunk) {
            var_dump("Load chunk {$k} of {$count}");

            /** @var Product[] $products */
            $products = [];
            foreach ($chunk as $i => $barcode) {
                $products[$i] = new Product($barcode, $factor);
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

                list($purchase, $retail) = $page->getPrice();

                $products[$i]->title->set($page->getTitle());
                $products[$i]->storage->set($page->getStorage());
                $products[$i]->purchase->set($purchase);
                $products[$i]->retail->set($retail);

                $xml->addProduct($products[$i]);
                $page->close();
                break;
            }
            sleep(1);
        }

        file_put_contents(\Yii::getAlias('@frontend/web/' . \Yii::$app->settings->get('eastmarine.error')), implode("\n", $errors));
        \Yii::$app->settings->set('eastmarine.date', time());
        \Yii::$app->settings->set('eastmarine.error_count', count($errors));

        $xml->save('@frontend/web/' . \Yii::$app->settings->get('eastmarine.xml'));
        \Yii::$app->settings->set('eastmarine.date', time());
        \Yii::$app->settings->set('eastmarine.is_job', false);

    }

    /**
     * @param Product[] $products
     * @return \yii\httpclient\Response[]
     */
    private function getResponses($products)
    {
        $requests = [];
        foreach ($products as $i => $product) {
            $requests[$i] = $this->client->post(self::URL . 'shop/search', [
                'filter' => ['art_no' => $product->barcode->value]
            ]);
        }
        return  $this->client->batch($requests);
    }


    private function auth()
    {
        $form = new LoginForm([
            'username' => \Yii::$app->settings->get('eastmarine.login'),
            'password' => \Yii::$app->settings->get('eastmarine.password'),
        ]);

        $response = $this->client->post($form->action, $form->data)->send();
        $page = new LoginPage($response->content);
        $isAuth = $page->isAuth();
        $page->close();

        return $isAuth;
    }


}