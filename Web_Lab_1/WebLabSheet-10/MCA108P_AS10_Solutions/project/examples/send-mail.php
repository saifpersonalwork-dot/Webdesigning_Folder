<?php
// Run: composer require phpmailer/phpmailer
require dirname(__DIR__) . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = getenv('SMTP_HOST') ?: 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = getenv('SMTP_USER');
    $mail->Password = getenv('SMTP_PASS');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom(getenv('SMTP_FROM') ?: 'noreply@example.com', 'My Project');
    $mail->addAddress('recipient@example.com');
    $mail->Subject = 'Account notification';
    $mail->Body = 'This is a test email from the PHP application.';
    $mail->send();
    echo 'Email sent';
} catch (Exception $e) {
    error_log('Mailer error: ' . $mail->ErrorInfo);
    echo 'Email could not be sent';
}
