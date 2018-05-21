<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],

    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
            // your other grid module settings
        ],
        'gridviewKrajee' => [
            'class' => '\kartik\grid\Module',
            // your other grid module settings
        ],
        'rbac' => [
            'class' => 'johnitvn\rbacplus\Module'
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',


        ]

    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            '*',
//            'site/*x',
//            'site/login',
// add or remove allowed actions to this list
        ],
    ],
    'components' => [
        'response' => [
            'format' => \yii\web\Response::FORMAT_RAW
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '1UlfLoSulQIgM5qLfFljtNfsGqs4NKdd',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'mdm\admin\models\User',
            'loginUrl' => ['admin/user/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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

//        'db' => ['class' => 'yii\db\Connection',
//            'dsn' => 'mysql:host=localhost;dbname=football',
//            'username' => 'root',
//            'password' => '',
//            'charset' => 'utf8'],
//        'db' => ['class' => 'yii\db\Connection',
//            'dsn' => 'mysql:host=31.31.196.89;dbname=u0487744_amd_sport',
//            'username' => 'u0487_adm-sport',
//            'password' => '123321',
//            'charset' => 'utf8'],
        'db' => ['class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=141.8.195.92;dbname=a0086640_amd-sportru',
            'username' => 'a0086640_pr',
            'password' => 'WindU160',
            'charset' => 'utf8'],

        'urlManager' => [
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => [
                '<controller:[\w\-]+>/<action:[\w\-]+>' => '<controller>/<action>',
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],


    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
