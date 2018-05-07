<?php


namespace core\jobs;


use core\parsers\east\Parser;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class EastParseJob  extends BaseObject implements JobInterface
{

    /**
     * @param Queue $queue which pushed and is handling the job
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function execute($queue)
    {
        \Yii::$app->settings->set('east.is_job', true);
        /** @var Parser $parser */
        $parser = \Yii::$container->get(Parser::class);

        $parser->run();
    }
}