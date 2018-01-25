<?php


namespace core\services\power;


use core\actions\power\LoginAction;

use core\actions\power\ProductAction;
use core\forms\power\LoginForm;

class Api
{
    /** @var LoginAction */
    private $login;

    /** @var ProductAction */
    private $product;

    public function __construct(
        LoginAction $loginAction,
        ProductAction $productAction
    )
    {
        $this->login = $loginAction;
        $this->product = $productAction;
    }

    public function login(LoginForm $form)
    {
        return $this->login->run($form);
    }


    public function getProducts()
    {
        return $this->product->run();
    }



}