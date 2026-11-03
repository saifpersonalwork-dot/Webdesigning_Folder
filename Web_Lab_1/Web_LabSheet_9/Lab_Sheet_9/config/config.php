<?php
declare(strict_types=1);

return [
    'db' => [
        'host' => '127.0.0.1',
        'name' => 'webapp_db',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'app_key' => 'CHANGE_THIS_TO_A_LONG_RANDOM_SECRET_KEY',
    'session_secure' => false, // Set true when using HTTPS.
    'upload_max_bytes' => 2 * 1024 * 1024,
    'smtp' => [
        'host' => '',
        'username' => '',
        'password' => '',
        'port' => 587,
        'from_email' => '',
        'from_name' => 'Web Lab App',
    ],
];
