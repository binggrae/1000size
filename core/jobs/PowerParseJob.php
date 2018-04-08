<?php


namespace core\jobs;


use core\parsers\power\Parser;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class PowerParseJob  extends BaseObject implements JobInterface
{

    /**
     * @param Queue $queue which pushed and is handling the job
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function execute($queue)
    {
        \Yii::$app->settings->set('power.is_job', true);
        /** @var Parser $parser */
        $parser = \Yii::$container->get(Parser::class);

        $parser->run();
    }
}