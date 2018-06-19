<?php

$config = [
    'id' => 'backend',
    'name' => 'JM_TEMPLATE',
    'defaultRoute' => 'site',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    "aliases" => [
        '@jeemoo' => dirname(dirname(__DIR__)) . '/vendor/jeemoo',
    ],
    'controllerMap'=>[
        'menu' => 'jeemoo\rbac\controllers\MenuController',
        'permission' => 'jeemoo\rbac\controllers\PermissionController',
        'role' => 'jeemoo\rbac\controllers\RoleController',
        'user-role' => 'jeemoo\rbac\controllers\UserRoleController',
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => '_backend',
        ],
        "authManager" => [
            "class" => 'jeemoo\rbac\common\AuthManager',
        ],
    ],
    'modules' => [
        'rbac' => [
            'class' => 'jeemoo\rbac\Module',
        ],
    ],
];


if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
        'generators' => [
            'jeemoo_generator' => 'jeemoo\generator\crud\Generator',
        ],
    ];
}
return $config;