<?php
require_once __DIR__ . '/config/db.php'; require_once __DIR__ . '/includes/helpers.php'; require_login();
$message='';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['photo'])){
    $id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
    $f=$_FILES['photo'];
    $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if(!$id || $f['error']!==UPLOAD_ERR_OK || $f['size']>2*1024*1024) $message='Invalid product or file (maximum 2 MB).';
    else {
        $mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
        if(!isset($allowed[$mime])) $message='Only JPG, PNG, and WEBP images are allowed.';
        else {
            $dir=__DIR__.'/uploads'; if(!is_dir($dir)) mkdir($dir,0755,true);
            $filename=bin2hex(random_bytes(16)).'.'.$allowed[$mime];
            if(move_uploaded_file($f['tmp_name'],$dir.'/'.$filename)){
                $stmt=$conn->prepare('UPDATE products SET image=? WHERE id=?');
                $stmt->bind_param('si',$filename,$id); $stmt->execute(); $message='Image uploaded.';
            } else $message='Upload failed.';
        }
    }
}
?>
<h2>Upload Product Image</h2><p><?= e($message) ?></p>
<form method="post" enctype="multipart/form-data">
Product ID: <input type="number" name="id" min="1" required>
Image: <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" required>
<button>Upload</button></form>
