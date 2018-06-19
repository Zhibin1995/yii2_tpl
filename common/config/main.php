<?php
$config = [
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Chongqing',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    "aliases" => [
        "@common" => dirname(__DIR__),
        '@frontend' => dirname(dirname(__DIR__)) . '/frontend',
        '@backend' => dirname(dirname(__DIR__)) . '/backend',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'request' => [
            'csrfParam' => '_csrf',
            'cookieValidationKey' => 'VJkQ_cDMh_YnniCfaobkofrF1Na2Mzwg',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=tpl_liu',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
        ],
        'session' => [
            'name' => '_session_id',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [

            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'viewPath' =>'@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.exmail.qq.com',
                'username' => 'aaa@qq.com',
                'password' => 'aaa',
                'port' => '465',
                'encryption' => 'SSL',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['aaa@qq.com' => 'aaa@qq.com']
            ],
        ],
    ],
    'params' => [
        'domain' => 'http://xa-admin.liu.com/',
        'webuploader' => [
            // 后端处理图片的地址，value 是相对的地址
            'uploadUrl' => 'image/upload',
            // 多文件分隔符
            'delimiter' => ',',
            // 基本配置
            'baseConfig' => [
                'defaultImage' => '/images/default.png',
                'disableGlobalDnd' => true,
                'accept' => [
                    'title' => 'Images',
                    'extensions' => 'jpg,png',
                    'mimeTypes' => 'image/*',
                ],
                'pick' => [
                    'multiple' => false,
                ],
            ],
        ],
    ],
];

return $config;