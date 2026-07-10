<?php
return [
    'driver' => 'smtp',
    'host'   => 'smtp.yourserver.com',
    'port'   => 587,
    'username' => 'your-email@yourdomain.com',
    'password' => 'your-password',
    'encryption' => 'tls',
    'from' => [
        'address' => 'no-reply@yourdomain.com',
        'name' => 'TMSolar Service Manager',
    ],
];