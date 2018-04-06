<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'common\bootstrap\SetUp',
        'queue'
    ],
	'timeZone' => 'Europe/Moscow', 
    'modules' => [
        'settings' => [
            'class' => 'pheme\settings\Module',
            'sourceLanguage' => 'en'
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@console/runtime/cache',
        ],
        'settings' => [
            'class' => 'pheme\settings\components\Settings'
        ],
        'queue' => [
            'class' => '\yii\queue\db\Queue',
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => '\yii\mutex\MysqlMutex',
            'ttr' => 6 * 60 * 60,
            'attempts' => 3,
        ],
    ],
];
