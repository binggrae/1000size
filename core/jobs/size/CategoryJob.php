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

        $links = array_chunk($this->links, 10);

        foreach ($links as $chunks) {

            $requests = [];
            foreach ($chunks as $link) {
                $requests[$link] = $this->client->get('https://opt.1000size.ru/' . $link);
            }

            $responses = $this->client->batch($requests);

            foreach ($responses as $link => $response) {
                if ($response->isOk) {
                    file_put_contents(\Yii::getAlias('@common/data/view.html'), $response->content);
                    $productPage = new ProductPage($response->content);
                    $data = $productPage->getData();

                    $product = new Products(get_object_vars($data));
                    $product->save();

                } else {
                    var_dump('Error ' . $link);
                    continue;
                }
            }
        }

        $log->end(count($this->links));
    }


}