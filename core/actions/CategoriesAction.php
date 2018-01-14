<?php


namespace core\actions;

use core\exceptions\RequestException;
use core\pages\CategoriesPage;
use core\services\Client;

class CategoriesAction
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function run()
    {
        $request = $this->client->get(CategoriesPage::URL)->send();
		file_put_contents(\Yii::getAlias('@common/data/t.html'), $request->content);
        if ($request->isOk) {
            $page = new CategoriesPage($request->content);
            return $page->getList();

        } else {
            throw new RequestException('Failed load root categories: ' . CategoriesPage::URL);
        }
    }


}