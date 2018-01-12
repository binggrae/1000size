<?php

namespace common\bootstrap;

use core\jobs\ParseJob;
use yii\base\BootstrapInterface;
use yii\httpclient\Client;
use yii\queue\ErrorEvent;
use yii\queue\Queue;

class SetUp implements BootstrapInterface
{

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->set(Client::class, function() use ($app) {
            return  new Client([
                'transport' => 'yii\httpclient\CurlTransport',
            ]);
        });

        \Yii::$app->queue->on(Queue::EVENT_AFTER_ERROR, function (ErrorEvent $event) {
            if ($event->job instanceof ParseJob) {
                \Yii::$app->settings->set('parser.is_job', 0);
            }
        });












    }

}