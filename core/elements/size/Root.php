<?php


namespace core\elements\size;


class Root
{
    public $link;
    public $title;


    public function  __construct($link, $title)
    {
        $this->link = 'https://opt.1000size.ru' . $link;
        $this->title = $title;
    }


}