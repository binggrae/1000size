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
     * @param $log_id
     * @param Root $category
     * @param $page
     * @return CategoryPage
     */
    public function run($log_id, $category, $page)
    {
        $url = $category->link . '?page=' . $page;
        $request = $this->client->get($url)->send();
        if ($request->isOk) {
            $page = new CategoryPage($request->content);
            $links = $page->getLinks();

            \Yii::$app->queue->push(new CategoryJob($this->client, [
                'links' => $links,
                'log_id' => $log_id,
                'log_link' => $url
            ]));
            return $page;
        } else {
            throw new RequestException('Failed load category: ' . $category->title);
        }
    }


}