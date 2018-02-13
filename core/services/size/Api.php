<?php


namespace core\services\size;


use core\actions\size\CategoriesAction;
use core\actions\size\LoginAction;
use core\actions\size\PagesAction;
use core\actions\size\ProductAction;

class Api
{
    /** @var LoginAction */
    private $login;

    /** @var PagesAction */
    private $pages;

    /** @var CategoriesAction */
    private $category;

    /** @var ProductAction */
    private $product;

    public function __construct(
        LoginAction $loginAction,
        CategoriesAction $categoryAction,
        PagesAction $pagesAction,
        ProductAction $productAction
    )
    {
        $this->login = $loginAction;
        $this->category = $categoryAction;
        $this->pages = $pagesAction;
        $this->product = $productAction;
    }

    public function login()
    {
        return $this->login->run();
    }


    public function getCategories()
    {
        return $this->category->run();
    }


    public function getPages($categories)
    {
        $this->pages->run($categories);
    }





}