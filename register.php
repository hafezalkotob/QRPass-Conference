<?php
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/qr.php';
require_once __DIR__ . '/src/sms_ir.php';
require_once __DIR__ . '/src/mailer.php';
require_once __DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php';
require_once 'header.php';

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
$uuid = substr(bin2hex(random_bytes(16)), 0, 10);
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

// Generate PDF Ticket
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('dejavusans', 'B', 20);
        $this->Cell(0, 15, 'کارت ورود همایش', 0, true, 'C', 0);
        $this->Ln(10);
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ditasia');
$pdf->SetTitle('کارت ورود همایش');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array('dejavusans', '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array('dejavusans', '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$pdf->SetFont('dejavusans', '', 12, '', true);

$pdf->AddPage();

// Add QR Code
$pdf->Image($qrPath, 15, 40, 50, 50, 'PNG');

// Add User Information
$pdf->SetXY(70, 40);
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0, 10, 'اطلاعات شرکت کننده:', 0, 1, 'R');

$pdf->SetXY(70, 50);
$pdf->SetFont('dejavusans', '', 12);
$pdf->Cell(0, 10, 'نام و نام خانوادگی: ' . $_POST['first_name'] . ' ' . $_POST['last_name'], 0, 1, 'R');

$pdf->SetXY(70, 60);
$pdf->Cell(0, 10, 'شماره موبایل: ' . $_POST['mobile'], 0, 1, 'R');

$pdf->SetXY(70, 70);
$pdf->Cell(0, 10, 'شرکت: ' . $_POST['company'], 0, 1, 'R');

$pdf->SetXY(70, 80);
$pdf->Cell(0, 10, 'تاریخ ثبت نام: ' . date('Y/m/d H:i'), 0, 1, 'R');

// Save PDF
$pdfPath = __DIR__ . '/assets/tickets/' . $uuid . '.pdf';
if (!is_dir(dirname($pdfPath))) {
    mkdir(dirname($pdfPath), 0775, true);
}
$pdf->Output($pdfPath, 'F');

// send SMS
sms_pattern_send($_POST['mobile'], SMS_PATTERN_ID, [
    'name'    => $_POST['first_name'],
    'family'  => $_POST['last_name'],
    'link'    => $uuid,
]);

// send Email if provided
if (!empty($_POST['email'])) {
    $body = "<p>سلام {$_POST['first_name']} عزیز،</p>
        <p>برای دریافت کارت ورود خود روی لینک زیر کلیک کنید:</p>
        <p><a href=\"{$ticketUrl}\">مشاهده کارت ورود</a></p>";
    send_ticket_email($_POST['email'], 'کارت ورود همایش', $body);
}

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body text-center p-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    <h2 class="mt-4 mb-3 text-success">ثبت‌نام موفق</h2>
                    <p class="lead mb-4">ثبت‌نام شما با موفقیت انجام شد.</p>
                    <p class="text-muted">لینک کارت ورود برای شما ارسال شد.</p>
                    <div class="mt-4">
                        <a href="assets/tickets/<?php echo $uuid; ?>.pdf" class="btn btn-primary" download>
                            <i class="bi bi-download"></i> دانلود کارت ورود
                        </a>
                        <a href="index.php" class="btn btn-outline-secondary ms-2">بازگشت به صفحه اصلی</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'footer.php';
?>
