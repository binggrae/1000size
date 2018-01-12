<?php


namespace core\actions;

use core\elements\ProductList;
use core\elements\Root;
use core\exceptions\RequestException;
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
     * @return ProductList
     */
    public function run($category, $page)
    {
        $url = $category->link . '?page=' . $page;
        $request = $this->client->get($url)->send();
        if ($request->isOk) {
            $page = new CategoryPage($request->content);
            $links = $page->getLinks();

            $products = [];

            foreach ($links as $link) {
                $request = $this->client->get($link)->send();
                if ($request->isOk) {
                    $productPage = new ProductPage($request->content);
                    $products[] = $productPage->getData();
                } else {
                    throw new RequestException('Failed load page: ' . $link);
                }
            }
            $list = new ProductList($products, $page->hasNext());
            return $list;
        } else {
            throw new RequestException('Failed load category: ' . $category->title);
        }
    }


}