<?php

namespace core\parsers\size\pages;


use core\parsers\size\Parser;
use yii\httpclient\Response;

class HomePage extends Page
{

    public static function create(Response $response)
    {
        if(isset($response->getHeaders()['location'])) {
            return new ProductPage($response->content);
        } else {
            return new SearchPage($response->content);
        }
    }

    public function getToken()
    {
        return $this->pq->find('#login__csrf_token')->attr('value');
    }

    public function getAuthAction()
    {
        return Parser::BASE_URL . 'signin?to=home';
    }

}