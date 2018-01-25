<?php


namespace core\actions\power;


use core\exceptions\RequestException;
use core\forms\power\LoginForm;
use core\pages\power\AuthPage;
use core\pages\power\HomePage;
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
        $request = $this->client->get(HomePage::URL)->send();
        if ($request->isOk) {
            $home = new HomePage($request->content);
            if ($home->isLogin()) {
                return true;
            }
        } else {
            throw new RequestException('Failed load login page');
        }
        $form->token = $home->getToken();
        $request = $this->client->post(AuthPage::URL, $form->getPostData())->send();
        if ($request->isOk) {
            $home = new HomePage($request->content);

            return $home->isLogin();
        } else {
            throw new RequestException('Failed auth');
        }
    }

}