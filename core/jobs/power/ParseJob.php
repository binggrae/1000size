<?php


namespace core\jobs\power;

use core\forms\power\LoginForm;
use core\services\power\Api;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class ParseJob extends BaseObject implements JobInterface
{

    public $login;

    public $password;

    /**
     * @var Api
     */
    private $api;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->api = \Yii::$container->get(Api::class);
    }

    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        $form = new LoginForm([
            'username' => $this->login,
            'password' => $this->password,
        ]);

        try {
            if ($this->api->login($form)) {
                $this->api->getProducts();
            } else {
                return;
            }
        } catch (\Exception $e) {
            throw $e;
        }


    }


}