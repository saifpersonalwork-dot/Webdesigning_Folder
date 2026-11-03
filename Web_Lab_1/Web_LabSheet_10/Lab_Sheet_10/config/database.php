<?php
declare(strict_types=1);

require_once __DIR__ . '/../app/core/Database.php';
$config = require __DIR__ . '/config.php';

try {
    $db = Database::instance($config['db']);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection is unavailable. Please try again later.');
}
