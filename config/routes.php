<?php

return [
    '/' => 'site/docs',
    '/json-schema' => 'site/json-schema',
    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
];