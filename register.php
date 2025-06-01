<?php
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/qr.php';
require_once __DIR__ . '/src/sms_ir.php';
require_once __DIR__ . '/src/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}

$required = ['first_name','last_name','gender','company','mobile'];
foreach ($required as $f) {
    if (empty($_POST[$f])) {
        header('Location: index.php?err=همه فیلدهای ستاره‌دار الزامی است'); exit;
    }
}

$pdo = get_db();
$stmt = $pdo->prepare("SELECT id FROM users WHERE mobile = ?");
$stmt->execute([$_POST['mobile']]);
if ($stmt->fetch()) {
    header('Location: index.php?err=شما قبلاً ثبت‌نام کرده‌اید'); exit;
}

// insert user
$uuid = bin2hex(random_bytes(16));
$stmt = $pdo->prepare("INSERT INTO users (uuid,first_name,last_name,gender,company,position,mobile,email,created_at) VALUES (?,?,?,?,?,?,?, ?, NOW())");
$stmt->execute([
    $uuid,
    $_POST['first_name'],
    $_POST['last_name'],
    $_POST['gender'],
    $_POST['company'],
    $_POST['position'] ?? null,
    $_POST['mobile'],
    $_POST['email'] ?? null
]);

// generate QR
$qrDir = __DIR__ . '/assets/qr';
if (!is_dir($qrDir)) mkdir($qrDir,0775,true);
$qrPath = $qrDir . "/{$uuid}.png";
$ticketUrl = (isset($_SERVER['HTTPS'])?'https':'http')."://".$_SERVER['HTTP_HOST']."/ticket.php?code={$uuid}";
generate_qr($ticketUrl, $qrPath);

// send SMS
//sms_pattern_send($_POST['mobile'], SMS_PATTERN_ID, ['link' => $ticketUrl]);
sms_pattern_send($_POST['mobile'], SMS_PATTERN_ID, [
    'name'    => $_POST['first_name'],
    'family'  => $_POST['last_name'],
    'link'    => $ticketUrl,
]);

// send Email if provided
if (!empty($_POST['email'])) {
    $body = "<p>سلام {$_POST['first_name']} عزیز،</p>
        <p>برای دریافت بلیت خود روی لینک زیر کلیک کنید:</p>
        <p><a href=\"{$ticketUrl}\">مشاهده بلیت</a></p>";
    send_ticket_email($_POST['email'], 'بلیت همایش', $body);
}

echo "ثبت‌نام با موفقیت انجام شد. لینک بلیت برای شما ارسال شد.";
