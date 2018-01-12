<?php


namespace core\services;


use core\actions\CategoriesAction;
use core\actions\LoginAction;
use core\actions\ProductAction;
use core\actions\RootAction;
use core\elements\Root;
use core\forms\LoginForm;

class Api
{
    /** @var LoginAction */
    private $login;

    /**
     * @var RootAction
     */
    private $root;
    /**
     * @var CategoriesAction
     */
    private $category;
    /**
     * @var ProductAction
     */
    private $product;

    public function __construct(
        LoginAction $loginAction,
        RootAction $rootAction,
        CategoriesAction $categoryAction,
        ProductAction $productAction
    )
    {
        $this->login = $loginAction;
        $this->root = $rootAction;
        $this->category = $categoryAction;
        $this->product = $productAction;
    }

    public function login(LoginForm $form)
    {
        return $this->login->run($form);
    }


    public function getCategories()
    {
        return $this->category->run();
    }


    public function getProducts($category, $page = 0)
    {
        return $this->product->run($category, $page);
    }


}