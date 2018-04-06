<?php


namespace core\jobs;


use core\parsers\bch\Api;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class BchParseJob  extends BaseObject implements JobInterface
{


    /**
     * @param Queue $queue which pushed and is handling the job
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function execute($queue)
    {
        \Yii::$app->settings->set('bch.is_job', true);
        /** @var Api $api */
        $api = \Yii::$container->get(Api::class);

        $api->parse();

    }
}