<?php


namespace core\actions\size;


use core\entities\size\Categories;
use core\entities\size\Products;
use core\pages\size\CategoryPage;
use core\services\Client;
use core\services\size\Api;

class PagesAction
{

    /** @var Client */
    private $client;

    /** @var Api */
    private $api;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getApi()
    {
        if (!$this->api) {
            $this->api = \Yii::$container->get(Api::class);
        }
        return $this->api;
    }


    /**
     * @param Categories[] $categories
     * @throws \yii\db\Exception
     */
    public function run($categories)
    {
        \Yii::$app->db->createCommand('UPDATE products SET status = status+1')->execute();
        foreach ($categories as $category) {
            $page = 0;
            do {
                $url = 'https://opt.1000size.ru' . $category->link . '?page=' . $page;
                var_dump($url);
                $request = $this->client->get($url)->send();
                $categoryPage = new CategoryPage($request->content);
                if (!$categoryPage->isLogin()) {
                    $this->getApi()->login();
                    continue;
                }
                foreach ($categoryPage->getLinks() as $link) {
                    $model = Products::findOne(['link' => $link]);
                    if (!$model) {
                        $model = new Products([
                            'link' => $link,
                            'category_id' => $category->id,
                            'status' => 0,
                            'barcode' => 'none',
                            'title' => 'none',
                            'storageM' => 0,
                            'storageV' => 0,
                            'purchase' => 0,
                            'retail' => 0,
                        ]);
                    }

                    $model->status = 0;
                    $model->save();
                };

                $page++;
            } while ($categoryPage->hasNext());
        }
    }
}