<?php

return [
    'id' => 'frontend',
    'defaultRoute' => 'site',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'loginUrl' => '/site/login',
            'identityClass' => 'common\models\Member',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => '_frontend',
        ],
//        'log' => [
//            'traceLevel' => 3,
//            'targets' => [
//                [
//                    'class' => 'yii\log\FileTarget',
//                    'levels' => ['info'],
//                    'categories' => ['wx*'],
//                    'logFile' => '@runtime/logs/wx_info.log',
//                    'logVars' => [],
//                ], [
//                    'class' => 'yii\log\FileTarget',
//                    'levels' => ['error', 'warning'],
//                    'categories' => ['wx*'],
//                    'logFile' => '@runtime/logs/wx_error.txt',
//                    'logVars' => [],
//                ],
//            ],
//        ],
    ]
];
