<?php
return [
    'class' => 'yii\swiftmailer\Mailer',
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => 'rest.prod@gmail.com',
        'password' => '12345Test',
        'port' => '465',
        'encryption' => 'ssl',
    ],
];