<?php


namespace core\elements\size;


class Root
{
    public $link;
    public $title;


    public function  __construct($link, $title)
    {
        $this->link = $link;
        $this->title = $title;
    }


}