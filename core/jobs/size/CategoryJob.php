<?php


namespace core\jobs\size;


use core\entities\logs\CategoryLog;
use core\entities\size\Products;
use core\pages\size\ProductPage;
use core\services\Client;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class CategoryJob extends BaseObject implements JobInterface
{
    public $links;

    public $log_id;
    public $log_link;
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
        $log = CategoryLog::start($this->log_id, $this->log_link);
        foreach ($this->links as $link) {
            $request = $this->client->get($link)->send();
            if ($request->isOk) {
                $productPage = new ProductPage($request->content);

                $product = new Products(get_object_vars($productPage->getData()));
                $product->save();
            } else {
                continue;
            }
        }
        $log->end(count($this->links));
    }


}