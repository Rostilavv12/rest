<?php
return [
    'class' => 'yii\swiftmailer\Mailer',
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => 'rest.dev@gmail.com',
        'password' => 'vj3UpJ123sfdDwYxFjNqfdsDS8FLh',
        'port' => '465',
        'encryption' => 'ssl',
    ],
];