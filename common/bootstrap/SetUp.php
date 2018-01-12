<?php

namespace common\bootstrap;

use core\entities\Task;
use core\jobs\CategoryJob;
use core\jobs\ParseJob;
use core\jobs\XlsJob;
use core\jobs\XmlJob;
use yii\base\BootstrapInterface;
use yii\httpclient\Client;
use yii\queue\ErrorEvent;
use yii\queue\ExecEvent;
use yii\queue\PushEvent;
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



        \Yii::$app->queue->on(Queue::EVENT_AFTER_PUSH, function (PushEvent $event) {
            if ($event->job instanceof CategoryJob) {
                $task = new Task([
                    'pid' => $event->id,
                ]);
                $task->save();
            }
        });

        \Yii::$app->queue->on(Queue::EVENT_AFTER_EXEC, function (ExecEvent $event) {
            if ($event->job instanceof CategoryJob) {
                Task::deleteAll(['pid' => $event->id]);

                if(Task::find()->count() == 0) {
                    $this->parse();
                }
            }
        });




        \Yii::$app->queue->on(Queue::EVENT_AFTER_ERROR, function (ErrorEvent $event) {
            if ($event->job instanceof ParseJob) {
                \Yii::$app->settings->set('parser.is_job', 0);
            }

            if ($event->job instanceof CategoryJob) {
                Task::deleteAll(['pid' => $event->id]);

                if(Task::find()->count() == 0) {
                    $this->parse();
                }
            }
        });



    }

    private function parse()
    {
        if(Task::find()->count() == 0) {
            \Yii::$app->queue->delay(5 * 60)->push(new XmlJob());

            \Yii::$app->queue->delay(5 * 60)->push(new XlsJob());
        }
    }

}