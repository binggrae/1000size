<?php


namespace core\services\size;


use core\actions\size\CategoriesAction;
use core\actions\size\LoginAction;
use core\actions\size\ProductAction;
use core\actions\size\RootAction;
use core\forms\size\LoginForm;

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


    public function getProducts($log_id, $category, $page = 0)
    {
        return $this->product->run($log_id, $category, $page);
    }


}