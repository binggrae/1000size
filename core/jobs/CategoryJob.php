<?php


namespace core\jobs;


use core\entities\Products;
use core\exceptions\RequestException;
use core\pages\ProductPage;
use core\services\Client;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class CategoryJob extends BaseObject implements JobInterface
{
    public $links;
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client, array $config = [])
    {
        parent::__construct($config);
        $this->client = $client;
    }

    public function execute($queue)
    {
        foreach ($this->links as $link) {
            $request = $this->client->get($link)->send();
            if ($request->isOk) {
                $productPage = new ProductPage($request->content);

                $product = new Products(get_object_vars($productPage->getData()));
                $product->save();

            } else {
                throw new RequestException('Failed load page: ' . $link);
            }
        }
    }


}