<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=dev_rest_db',
    'username' => 'rest_user',
    'password' => 'ueS&1Ex^e9D#Lpehdu@0D7J^sK8#zj',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => !YII_DEBUG,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
