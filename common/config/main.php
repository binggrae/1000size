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
            'class' => '\yii\queue\file\Queue',
            'path' => '@console/runtime/queue',
            'ttr' => 0
        ],
        
    ],
];
