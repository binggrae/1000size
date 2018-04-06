<?php


namespace core\services;


use core\exceptions\RequestException;
use core\pages\HomePage;
use \yii\httpclient\Client as BaseClient;
use yii\httpclient\Request;

class Client
{
    /**
     * @var BaseClient
     */
    private $client;

    public function __construct(BaseClient $client)
    {
        $this->client = $client;
    }



    public function get($url, $data = [])
    {
        return $this->send('get', $url, $data);

    }

    public function post($url, $data = [])
    {
        return $this->send('post', $url, $data);
    }


    /**
     * @param $requests
     * @return \yii\httpclient\Response[]
     */
    public function batch($requests)
    {
        return $this->client->batchSend($requests);
    }


    /**
     * @param $method
     * @param $url
     * @param array $data
     * @return Request
     */
    private function send($method, $url, $data = [])
    {
        $opt = [
            CURLOPT_COOKIEJAR => \Yii::getAlias('@common/data/cookie.txt'),
            CURLOPT_COOKIEFILE => \Yii::getAlias('@common/data/cookie.txt'),
            CURLOPT_TIMEOUT => 100,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
//            CURLOPT_VERBOSE => true,
        ];


//        if($method == 'post') {
//            $opt[CURLOPT_VERBOSE] = true;
//        }

//        $this->client->batchSend()
        $response = $this->client->createRequest()
            ->setMethod($method)
            ->setUrl($url)
            ->setData($data)
            ->setOptions($opt);

        return $response;
    }

}