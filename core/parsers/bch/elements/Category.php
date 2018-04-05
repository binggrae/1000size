<?php


namespace core\parsers\bch\elements;


use core\parsers\bch\Api;

class Category
{
    private $url;

    public function __construct(string $url)
    {
        $this->url = Api::URL . $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

}