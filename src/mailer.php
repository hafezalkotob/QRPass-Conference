<?php
require_once __DIR__ . '/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_ticket_email(string $to, string $subject, string $bodyHtml): bool
{
    $mail = new PHPMailer(true);

    try {
        // برای بررسی خطاها می‌توانی این خط را موقت فعال کنی:
        // $mail->SMTPDebug = 2;

        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->CharSet    = 'UTF-8';

        // رمزنگاری بر اساس تنظیمات
        $encryption = defined('MAIL_ENCRYPTION') ? MAIL_ENCRYPTION : 'tls';
        if ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mail->Port = intval(MAIL_PORT); // معمولاً 587 یا 465

        // فرستنده و گیرنده
        $mail->setFrom(MAIL_FROM, 'Conference Team');
        $mail->addAddress($to);

        // محتوا
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;

        $mail->send();
        return true;

    } catch (Exception $e) {
        // ذخیره خطا در لاگ برای بررسی
        file_put_contents(__DIR__.'/../mail.log',
            date('Y-m-d H:i:s').' '.$e->getMessage()."\n", FILE_APPEND);
        return false;
    }
}
