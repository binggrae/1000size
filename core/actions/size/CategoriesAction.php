<?php


namespace core\actions\size;

use core\entities\size\Categories;
use core\exceptions\RequestException;
use core\pages\size\CategoriesPage;
use core\pages\size\HomePage;
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
        $request = $this->client->get(HomePage::URL)->send();
        if ($request->isOk) {
            $page = new CategoriesPage($request->content);

            \Yii::$app->db->createCommand('UPDATE size_categories SET status = status+1')->execute();
            foreach ($this->getItem($page->getCategories()) as $category) {
                $model = Categories::findOne(['link' => $category]);
                if (!$model) {
                    $model = Categories::create(
                        $category['id'],
                        $category['link'],
                        $category['title']
                    );
                }
                $model->status = 0;
                $model->save();
            }
            return true;
        } else {
            throw new RequestException('Failed load root categories: ' . CategoriesPage::URL);
        }
    }

    public function getItem($parent)
    {
        if (count($parent['items'])) {
            foreach ($parent['items'] as $child) {
                yield from $this->getItem($child);
            }
        } else {
            yield $parent;
        }
    }

}