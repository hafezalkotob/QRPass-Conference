<?php
require_once __DIR__ . '/src/db.php';
if (!isset($_GET['code'])) { http_response_code(400); echo 'کد نامعتبر'; exit; }
$pdo = get_db();
$stmt = $pdo->prepare("SELECT * FROM users WHERE uuid = ?");
$stmt->execute([$_GET['code']]);
$user = $stmt->fetch();
if (!$user) { http_response_code(404); echo 'کاربر یافت نشد'; exit; }
$qrPath = "assets/qr/{$user['uuid']}.png";
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><title>بلیت همایش</title></head>
<body style="text-align:center;font-family:Tahoma">
<h2>بلیت ورود به همایش</h2>
<p><?=htmlspecialchars($user['first_name'].' '.$user['last_name'])?></p>
<img src="<?=htmlspecialchars($qrPath)?>" alt="QR Code">
</body>
</html>
