<?php


namespace core\actions\east;

use core\entities\east\Products;
use core\jobs\east\ProductJob;
use core\pages\east\ProductPage;
use core\services\Client;
use core\services\Xml;
use core\services\XmlImport;
use yii\helpers\VarDumper;
use yii\httpclient\Response;

class LoadAction
{
    /**
     * @var Client
     */
    private $client;

    private $products = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function run()
    {
        $xmlImporter = new XmlImport(\Yii::$app->settings->get('eastmarine.list'));
        $list = $xmlImporter->getList();
        $chunks = array_chunk($list, 5, true);
        $errors = [];

        foreach ($chunks as $chunk) {
            $requests = [];
            foreach ($chunk as $id => $item) {
                $requests[$id] = $this->client->post(ProductPage::URL, [
                    'filter' => ['art_no' => $item]
                ]);
            }
            /** @var Response[] $responses */
            $responses = $this->client->batch($requests);

            foreach ($responses as $id => $response) {
                var_dump($id);
                if($response->isOk) {
                    $page = new ProductPage($list[$id], $response->content);
                    $data = $page->getData();
                    if($data) {
                        $this->products[] = $data;
                    } else {
                        $errors[] = $list[$id];
                    }
                    $page->close();
                } else {
                    var_dump('fail');
                    var_dump($response->getStatusCode());
                    die();
                }

            }
            sleep(2);
        }

        $xmlService = new Xml($this->products);
        $xmlService->generate();
        $xmlService->save('@frontend/web/' . \Yii::$app->settings->get('eastmarine.xml'));

        file_put_contents('@frontend/web/' . \Yii::$app->settings->get('eastmarine.error'), VarDumper::dumpAsString($errors));
        \Yii::$app->settings->set('automaster.date', time());
        \Yii::$app->settings->set('automaster.error_count', count($errors));

        return true;
    }
}