<?php
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/header.php';

if (!isset($_GET['code'])) { http_response_code(400); echo 'کد نامعتبر'; exit; }
$pdo = get_db();
$stmt = $pdo->prepare("SELECT * FROM users WHERE uuid = ?");
$stmt->execute([$_GET['code']]);
$user = $stmt->fetch();
if (!$user) { http_response_code(404); echo 'کاربر یافت نشد'; exit; }
$qrPath = "assets/qr/{$user['uuid']}.png";
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h1 class="h4 mt-2 mb-4">بلیت ورود به همایش</h1>
                    <div class="mb-4">
                        <img src="<?=htmlspecialchars($qrPath)?>" alt="QR Code" class="img-fluid" style="max-width: 256px;">
                    </div>
                    <p class="text-muted">لطفاً این QR کد را در محل برگزاری همایش به مسئولین نمایش دهید.</p>

                    <hr class="my-4">

                    <div class="row text-muted small">
                        <div class="col-md-6 text-start">
                            <div class="mb-2">
                                <strong>نام و نام خانوادگی:</strong> <?=htmlspecialchars($user['first_name'].' '.$user['last_name'])?>
                            </div>

                            <div class="mb-2">
                                <strong>شماره موبایل:</strong> <?=htmlspecialchars($user['mobile'])?>
                            </div>

                            <?php if (!empty($user['email'])): ?>
                            <div class="mb-2">
                                <strong>ایمیل:</strong> <?=htmlspecialchars($user['email'])?>
                            </div>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-6 text-start">
                            <div class="mb-2">
                                <strong>شرکت:</strong> <?=htmlspecialchars($user['company'])?>
                            </div>

                            <?php if (!empty($user['position'])): ?>
                            <div class="mb-2">
                                <strong>سمت:</strong> <?=htmlspecialchars($user['position'])?>
                            </div>
                            <?php endif; ?>

                            <div class="mb-2">
                                <strong>تاریخ ثبت‌نام:</strong> <?=date('Y/m/d', strtotime($user['created_at']))?>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="text-muted small">
                        <div class="mb-2 text-start">
                            <strong>زمان:</strong> پنجشنبه 29 خرداد 1404، ساعت 8:30 تا 17
                        </div>

                        <div class="mb-2 text-start">
                            <strong>مکان:</strong> تهران، خیابان فرشته، شماره 46، خانه همایش
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>
