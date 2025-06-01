<?php
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/sms_ir.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['code'])) { http_response_code(400); echo json_encode(['error'=>'missing code']); exit; }

$pdo = get_db();
$pdo->beginTransaction();
$stmt = $pdo->prepare("SELECT id, first_name, mobile, last_name FROM users WHERE uuid = ? FOR UPDATE");
$stmt->execute([$_GET['code']]);
$user = $stmt->fetch();
if (!$user) { $pdo->rollBack(); http_response_code(404); echo json_encode(['error'=>'not found']); exit; }

// check attendance
$stmt = $pdo->prepare("SELECT id FROM attendance WHERE user_id = ?");
$stmt->execute([$user['id']]);
if (!$stmt->fetch()) {
    $stmt = $pdo->prepare("INSERT INTO attendance (user_id, attended_at) VALUES (?, NOW())");
    $stmt->execute([$user['id']]);
    sms_pattern_send($user['mobile'], WELCOME_PATTERN_ID, [
        'name'    => $_POST['first_name'],
        'family'  => $_POST['last_name'],
        'link'    => $user['uuid'],
    ]);
}
$pdo->commit();
echo json_encode(['ok'=>true]);
