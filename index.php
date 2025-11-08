<?php
// send_email.php
// PHPMailer template. Requires composer install: composer require phpmailer/phpmailer
// Place this file in same folder as index.html and run composer on server to create vendor/.
// Edit $smtpUser and $smtpPass with your Gmail and App Password (do NOT commit real password to public repo).

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if(!$data){
    http_response_code(400);
    echo json_encode(['status'=>'error','msg'=>'Invalid JSON or no input']);
    exit;
}

$name    = $data['name'] ?? 'Candidate';
$mobile  = $data['mobile'] ?? '';
$batch   = $data['batch'] ?? '';
$college = $data['college'] ?? '';
$year    = $data['year'] ?? '';
$course  = $data['course'] ?? '';
$score   = $data['score'] ?? '';
$total   = $data['total'] ?? '';
$certificateDataUrl = $data['certificateDataUrl'] ?? null;

$toEmail = 'abhisheksharma60884@gmail.com'; // CHANGE only if you want a different recipient

$smtpHost = 'smtp.gmail.com';
$smtpPort = 587;
$smtpUser = 'abhisheksharma60884@gmail.com'; // your Gmail
$smtpPass = 'REPLACE_WITH_YOUR_APP_PASSWORD'; // <-- REPLACE this with your App Password

$html = "<h2>BGT Assessment Result</h2>";
$html .= "<p><strong>Name:</strong> ".htmlspecialchars($name)."</p>";
$html .= "<p><strong>Mobile:</strong> ".htmlspecialchars($mobile)."</p>";
$html .= "<p><strong>Batch:</strong> ".htmlspecialchars($batch)."</p>";
$html .= "<p><strong>College:</strong> ".htmlspecialchars($college)."</p>";
$html .= "<p><strong>Year:</strong> ".htmlspecialchars($year)."</p>";
$html .= "<p><strong>Course:</strong> ".htmlspecialchars($course)."</p>";
$html .= "<p><strong>Score:</strong> ".htmlspecialchars($score).' / '.htmlspecialchars($total)."</p>";
$html .= "<p>Time: ".date('d-M-Y H:i:s')."</p>";

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = $smtpHost;
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpUser;
    $mail->Password   = $smtpPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $smtpPort;

    $mail->setFrom('no-reply@yourdomain.com', 'BGT Assessment');
    $mail->addAddress($toEmail);

    $mail->isHTML(true);
    $mail->Subject = "BGT Assessment Result — {$name} — Score: {$score}/{$total}";
    $mail->Body    = $html;
    $mail->AltBody = strip_tags($html);

    if($certificateDataUrl && preg_match('/^data:image\/png;base64,/', $certificateDataUrl)){
        $parts = explode(',', $certificateDataUrl);
        $binary = base64_decode($parts[1]);
        $tmpFile = sys_get_temp_dir() . '/cert_'.uniqid().'.png';
        file_put_contents($tmpFile, $binary);
        $mail->addAttachment($tmpFile, preg_replace('/\s+/', '_', $name).'_certificate.png');
    }

    $mail->send();

    if(isset($tmpFile) && file_exists($tmpFile)) @unlink($tmpFile);

    echo json_encode(['status'=>'ok','msg'=>'Email sent']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status'=>'error','msg'=>'Mailer Error: '.$mail->ErrorInfo]);
}
