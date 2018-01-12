<?php


namespace core\actions;

use core\elements\ProductList;
use core\elements\Root;
use core\exceptions\RequestException;
use core\jobs\CategoryJob;
use core\pages\CategoriesPage;
use core\pages\CategoryPage;
use core\pages\ProductPage;
use core\services\Client;

class ProductAction
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    /**
     * @param Root $category
     * @param $page
     * @return CategoryPage
     */
    public function run($category, $page)
    {
        $url = $category->link . '?page=' . $page;
        var_dump($url);
        $request = $this->client->get($url)->send();
        if ($request->isOk) {
            $page = new CategoryPage($request->content);
            $links = $page->getLinks();

            \Yii::$app->queue->push(new CategoryJob($this->client, [
                'links' => $links
            ]));
            return $page;
        } else {
            throw new RequestException('Failed load category: ' . $category->title);
        }
    }


}