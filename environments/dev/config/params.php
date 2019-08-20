<?php

return [
    'adminEmail' => 'admin@example.com',
    'fromEmail' => 'rest.dev@gmail.com',
    'jwt' => [
        'key' => 'mOjsQuWGea7aMZkWjnWR55S4jSXtGCKe7ltUivgtRF8=',
        'algorithm' => 'HS256',
        'exp' => null,
        'lifetime' => 60 * 60,
        'lifetimeRememberMe' => 60 * 60 * 24 * 30,
        'lifetimeResetPass' => 60 * 20,
    ],
    'frontendDomain' => 'front.dev_rest.com.ua',
    'adminDomain' => 'back.dev_rest.com.ua',
];
