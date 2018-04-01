<?php


namespace core\services\east;


use core\actions\east\LoginAction;

use core\actions\east\LoadAction;
use core\forms\east\LoginForm;

class Api
{
    /** @var LoginAction */
    private $login;

    /** @var LoadAction */
    private $load;

    public function __construct(
        LoginAction $loginAction,
        LoadAction $productAction
    )
    {
        $this->login = $loginAction;
        $this->load = $productAction;
    }

    public function login(LoginForm $form)
    {
        return $this->login->run($form);
    }


    public function load()
    {
        return $this->load->run();
    }


}