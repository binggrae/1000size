<?php

namespace common\bootstrap;

use core\entities\power\Products;
use core\entities\Task;
use core\jobs\power\ProductJob as PowerProductJob;
use core\jobs\power\XlsJob as PowerXlsJob;
use core\jobs\power\XmlJob as PowerXmlJob;
use core\jobs\size\ParseJob;
use core\jobs\size\ProductJob as SizeProductJob;
use core\jobs\size\XlsJob as SizeXlsJob;
use core\jobs\size\XmlJob as SizeXmlJob;
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

        $container->set(Client::class, function () use ($app) {
            return new Client([
                'transport' => 'yii\httpclient\CurlTransport',
            ]);
        });


        \Yii::$app->queue->on(Queue::EVENT_AFTER_PUSH, function (PushEvent $event) {
            if ($event->job instanceof SizeProductJob) {
                $task = new Task([
                    'pid' => $event->id,
                ]);
                $task->save();
            }
        });

        \Yii::$app->queue->on(Queue::EVENT_AFTER_EXEC, function (ExecEvent $event) {
            if ($event->job instanceof SizeProductJob) {
                Task::deleteAll(['pid' => $event->id]);

                if (Task::find()->count() == 0) {
                    $this->parse();
                }
            }

            if ($event->job instanceof PowerProductJob) {
                if (!Products::find()->where(['status' => Products::STATUS_IN_JOB])->count()) {
                    \Yii::$app->queue->priority(2000)->push(new PowerXmlJob());
                }
            }
        });


        \Yii::$app->queue->on(Queue::EVENT_AFTER_ERROR, function (ErrorEvent $event) {
            if ($event->job instanceof ParseJob) {
                \Yii::$app->settings->set('parser.is_job', 0);
            }

            if ($event->job instanceof SizeProductJob) {
                if (Task::find()->count() == 0) {
                    $this->parse();
                }
            }

            if ($event->job instanceof PowerProductJob) {
                if (!Products::find()->where(['status' => Products::STATUS_IN_JOB])->count()) {
                    \Yii::$app->queue->priority(2000)->push(new PowerXmlJob());
                }
            }
        });


    }

    private function parse()
    {

        \Yii::$app->settings->set('parser.date', time());
        \Yii::$app->settings->set('parser.is_job', 0);

        \Yii::$app->queue->priority(2000)->push(new SizeXmlJob());

    }

}