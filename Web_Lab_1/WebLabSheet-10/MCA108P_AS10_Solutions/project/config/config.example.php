<?php
return [
    'db' => [
        'host' => '127.0.0.1',
        'name' => 'mca108p_app',
        'user' => 'mca108p_user',
        'pass' => 'CHANGE_THIS_PASSWORD',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'base_url' => 'https://myproject.local',
        'upload_dir' => dirname(__DIR__, 2) . '/private_uploads',
        'api_token' => 'CHANGE_TO_A_LONG_RANDOM_TOKEN',
        'session_secure' => true,
    ],
];
