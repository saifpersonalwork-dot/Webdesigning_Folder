<?php
// Put equivalent settings in the production php.ini / virtual-host configuration.
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', dirname(__DIR__) . '/storage/php-errors.log');
error_reporting(E_ALL);
