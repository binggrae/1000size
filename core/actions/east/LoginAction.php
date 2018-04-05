<?php


namespace core\actions\east;


use core\exceptions\RequestException;
use core\forms\east\LoginForm;
use core\pages\east\AuthPage;
use core\pages\east\HomePage;
use core\services\Client;

class LoginAction
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param LoginForm $form
     * @return bool
     */
    public function run($form)
    {
        $request = $this->client->post(AuthPage::URL, $form->getPostData())->send();
        if ($request->isOk) {
            $home = new AuthPage($request->content);
            $isLogin = $home->isLogin();
            $home->close();
            return $isLogin;
        } else {
			var_dump($request->getStatusCode());
            throw new RequestException('Failed auth');
        }
    }

}