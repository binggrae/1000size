<?php


namespace core\actions\size;

use core\elements\size\Root;
use core\exceptions\RequestException;
use core\jobs\size\ProductJob;
use core\pages\size\CategoryPage;
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
        var_dump($url);
        $request = $this->client->get($url)->send();
        if ($request->isOk) {
            $page = new CategoryPage($request->content);
            $links = $page->getLinks();
            var_dump(count($links));

            \Yii::$app->queue->push(new ProductJob($this->client, [
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