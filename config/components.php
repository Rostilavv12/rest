<?php
$db = require __DIR__ . '/db.php';
$mail = require __DIR__ . '/mail.php';
$routes = require __DIR__ . '/routes.php';

return [
    'request' => [
        'cookieValidationKey' => 'AzvuLNkmVXV1H8j62bkG7tJ11kbUEn2c',
        'parsers' => [
            'application/json' => 'yii\web\JsonParser',
        ],
        'enableCsrfValidation' => false,
    ],
    'cache' => [
        'class' => 'yii\caching\FileCache',
    ],
    'user' => [
        'identityClass' => 'app\models\User',
        'enableAutoLogin' => true,
        'enableSession' => false,
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'mailer' => $mail,
    'db' => $db,
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => $routes,
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
];