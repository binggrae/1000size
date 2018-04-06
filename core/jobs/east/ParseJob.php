<?php


namespace core\jobs\east;

use core\forms\east\LoginForm;
use core\services\east\Api;
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
    }

    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        $this->api = \Yii::$container->get(Api::class);
        $form = new LoginForm([
            'username' => $this->login,
            'password' => $this->password,
        ]);
        try {
            if ($this->api->login($form)) {
                $this->api->load();

            } else {
                var_dump('no');
            }
        } catch (\Exception $e) {
            throw $e;
        }

        \Yii::$app->settings->set('east.is_job', true);

    }


}