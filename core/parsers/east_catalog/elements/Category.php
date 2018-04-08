<?php


namespace core\parsers\east_catalog\elements;


use core\parsers\east_catalog\Parser;

class Category
{
    private $url;

    public function __construct(string $url)
    {
        $this->url = Parser::URL . $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

}