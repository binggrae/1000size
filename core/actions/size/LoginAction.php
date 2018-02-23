<?php


namespace core\actions\size;


use core\exceptions\RequestException;
use core\forms\size\LoginForm;
use core\pages\size\AuthPage;
use core\pages\size\HomePage;
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

    public function run()
    {
        var_dump('login');
        $form = new LoginForm([
            'login' => \Yii::$app->settings->get('parser.login'),
            'password' => \Yii::$app->settings->get('parser.password'),
        ]);

        do {
            $request = $this->client->get(HomePage::URL)->send();
            if ($request->isOk) {
                $home = new HomePage($request->content);
                if ($home->isLogin()) {
                    return true;
                }
            } elseif ($request->getStatusCode() == 429) {
                var_dump('SLEEP');
                sleep(10);
                continue;
            } else {
                throw new RequestException('Failed load login page');
            }
            $form->token = $home->getToken();

            $request = $this->client->post(AuthPage::URL, $form->getPostData())->send();
            if ($request->isOk) {
                $home = new HomePage($request->content);

                return $home->isLogin();
            } elseif ($request->getStatusCode() == 429) {
                var_dump('SLEEP');
                sleep(10);
                continue;
            } else {
                throw new RequestException('Failed auth');
            }
        } while (1);

    }

}