<?php


namespace core\jobs\size;


use core\entities\logs\ParserLog;
use core\entities\size\Products;
use core\forms\size\LoginForm;
use core\services\size\Api;
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

    public function __construct(Api $api, array $config = [])
    {
        parent::__construct($config);
        $this->api = $api;
    }

    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        $log = ParserLog::start();
        if (\Yii::$app->settings->get('parser.is_job')) {
            $log->error(ParserLog::CODE_ALREADY_RUNNING);
            return;
        }

        \Yii::$app->settings->set('parser.is_job', 1);

        $form = new LoginForm([
            'login' => $this->login,
            'password' => $this->password
        ]);

        Products::deleteAll();

        try {
            $categories = [];
            if ($this->api->login($form)) {
                $categories = $this->api->getCategories();
                foreach ($categories as $category) {
                    $page = 1;

                    do {
                        $categoryPage = $this->api->getProducts($log->id, $category, $page);
                        $page++;
                    } while ($categoryPage->hasNext());
                }
            } else {
                $this->end();
                $log->error(ParserLog::CODE_BAD_LOGIN);
                return;
            }
        } catch (\Exception $e) {
            $log->error(ParserLog::CODE_UNKNOWN, $e->getMessage());
            throw $e;
        }

        $this->end();

        $log->end(count($categories));
    }


    public function end()
    {
        \Yii::$app->settings->set('parser.date', time());

        \Yii::$app->settings->set('parser.is_job', 0);
    }


}