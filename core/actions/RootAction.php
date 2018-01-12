<?php


namespace core\actions;


use core\exceptions\RequestException;
use core\pages\HomePage;
use core\services\Client;

class RootAction
{
    private $homeUrl = 'http://opt.1000size.ru/';

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return \core\elements\Root[]
     */
    public function run()
    {
        $request = $this->client->get(HomePage::URL)->send();
        if($request->isOk) {
            $page = new HomePage($request->content);

            return $page->getRoots();

        } else {
            throw new RequestException('Failed load roots categories: ' . HomePage::URL);
        }

    }


}