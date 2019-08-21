<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => [
            'v1/auth',
        ],
        'extraPatterns' => [
            'POST login' => 'login',
            'POST logout' => 'logout',
            'POST reset-password' => 'reset-password',
            'POST forgot-password' => 'forgot-password',
            'GET check-reset-token' => 'check-reset-token',
        ],
        'except' => ['index', 'create', 'update', 'delete']
    ],
    '/' => 'site/docs',
    '/json-schema' => 'site/json-schema',
    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

    'OPTIONS <module:\w+>/<controller:\w+>/<action:\w+>/<id>' => '<module>/<controller>/options',
    'OPTIONS <module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/options',
    '<module:\w+>/<controller:\w+>/<action:\w+>/<id>' => '<module>/<controller>/<action>',
];