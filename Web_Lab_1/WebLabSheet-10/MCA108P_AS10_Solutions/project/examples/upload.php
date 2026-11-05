<?php
require dirname(__DIR__) . '/config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['file'])) {
    exit('Upload a file using a multipart/form-data POST form.');
}
$file = $_FILES['file'];
if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 2 * 1024 * 1024) {
    http_response_code(400); exit('Upload failed or file exceeds 2 MB.');
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']);
$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'application/pdf' => 'pdf'];
if (!isset($allowed[$mime])) {
    http_response_code(415); exit('File type not allowed.');
}

$directory = $config['app']['upload_dir'];
if (!is_dir($directory) && !mkdir($directory, 0700, true)) {
    throw new RuntimeException('Cannot create upload directory');
}
$filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
if (!move_uploaded_file($file['tmp_name'], $directory . DIRECTORY_SEPARATOR . $filename)) {
    http_response_code(500); exit('Could not save file.');
}
echo 'Upload successful.';
