<?php


namespace core\parsers\bch;


use core\parsers\bch\elements\Category;
use core\parsers\bch\elements\Product;
use core\parsers\bch\forms\LoginForm;
use core\parsers\bch\pages\ItemPage;
use core\parsers\bch\pages\ListPage;
use core\services\Client;
use core\services\exports\Xml;
use yii\web\Cookie;


class Api
{
    const URL = 'https://bch5.ru/';
    /** @var Client */
    private $client;

    /** @var Category[] */
    private $categories = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->categories[] = new Category('propellers.php');
        $this->categories[] = new Category('parts_outboard.php');
        $this->categories[] = new Category('parts_snow.php');
        $this->categories[] = new Category('accessories.php?acc_id=1');
    }


    public function parse()
    {
        $this->auth();
        $xml = new Xml(['barcode', 'title', 'unit', 'storages', 'purchase', 'retail']);

        foreach ($this->categories as $category) {
            foreach ($this->getItems($category) as $items) {
                $this->loadItems($items);

                foreach ($items as $item) {
                    try {
                        $xml->addProduct($item);
                    } catch (\Exception $e) {
                        var_dump($item);
                        var_dump($e->getMessage());
                        die();
                    }
                }
            }
        }
        $xml->save(\Yii::getAlias('@frontend/web/' . \Yii::$app->settings->get('bch.xml')));
        \Yii::$app->settings->set('bch.date', time());
    }


    /**
     * @param Category $category
     * @return Product[][]|\Generator
     */
    private function getItems(Category $category)
    {
        $categories = [];
        $categories[] = $category;
        foreach ($categories as &$category) {
            var_dump('Load page: ' . $category->getUrl());
            $response = $this->client->get($category->getUrl())->send();
            $page = new ListPage($response->content);

            if ($page->isParent()) {
                foreach ($page->getChildren() as $child) {
                    $categories[] = $child;
                }
            } else {
                yield $page->getList();
            }

            $page->close();
        }
    }

    /**
     * @param Product[] $items
     * @throws \Exception
     */
    private function loadItems(array &$items)
    {
        $chunks = array_chunk($items, 7, true);
        $count = count($chunks);

        foreach ($chunks as $c => $chunk) {
            var_dump('Load chunk: ' . $c . ' of ' . $count);
            $requests = [];
            foreach ($chunk as $i => $item) {
                $requests[$i] = $this->client->get($item->link);
            }

            $responses = $this->client->batch($requests);

            foreach ($responses as $i => $response) {
                if (!$response->isOk) {
                    var_dump($response->getStatusCode());
                    continue;
                }
                $page = new ItemPage($response->content);
                try {
                    $items[$i]->barcode->set($page->getBarcode());
                    $items[$i]->title->set($page->getTitle());
                    $items[$i]->purchase->set($page->getPurchase());
                    $items[$i]->retail->set($page->getRetail());
                    $items[$i]->storages = $page->getStorages();

                } catch (\Exception $e) {
                    var_dump($items[$i]->link);
                    throw $e;
                }
                $page->close();
            }
        }

    }

    private function auth()
    {
        $this->client->get(self::URL . 'cabinet.php')->addCookies([
            [
                'name' => 'my_name5',
                'value' => 'info@west-marine.ru'
            ],
            [
                'name' => 'my_name3',
                'value' => '7366'
            ],
            [
                'name' => 'my_name6',
                'value' => 'info@west-marine.ru'
            ]
        ])->send();
    }

}