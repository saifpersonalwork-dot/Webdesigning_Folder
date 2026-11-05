<?php
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$url = filter_var($_POST['url'] ?? '', FILTER_VALIDATE_URL);
$age = filter_var($_POST['age'] ?? null, FILTER_VALIDATE_INT);
$name = trim(filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS));

if (!$email || !$url || $age === false || $age === null || $name === '') {
    http_response_code(422);
    exit('Please enter valid email, URL, integer age, and name.');
}
echo 'Input is valid.';
