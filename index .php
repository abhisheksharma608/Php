<?php
// send_email_simple.php
// Simple fallback: uses PHP mail() — many hosts need proper mail configuration for this to work.

header('Content-Type: application/json; charset=utf-8');
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if(!$data){
    http_response_code(400);
    echo json_encode(['status'=>'error','msg'=>'Invalid JSON']);
    exit;
}

$name = $data['name'] ?? 'Candidate';
$mobile = $data['mobile'] ?? '';
$batch = $data['batch'] ?? '';
$college = $data['college'] ?? '';
$year = $data['year'] ?? '';
$course = $data['course'] ?? '';
$score = $data['score'] ?? '';
$total = $data['total'] ?? '';

$to = 'abhisheksharma60884@gmail.com';
$subject = "BGT Result - {$name} - {$score}/{$total}";

$body = "<h2>BGT Assessment Result</h2>";
$body .= "<p><strong>Name:</strong> ".htmlspecialchars($name)."</p>";
$body .= "<p><strong>Score:</strong> ".htmlspecialchars($score)." / ".htmlspecialchars($total)."</p>";
$body .= "<p><strong>Details:</strong> Mobile: ".htmlspecialchars($mobile)." | Batch: ".htmlspecialchars($batch)." | College: ".htmlspecialchars($college)."</p>";
$body .= "<p>Time: ".date('d-M-Y H:i:s')."</p>";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: BGT Assessment <no-reply@yourdomain.com>\r\n";

$ok = mail($to, $subject, $body, $headers);
if($ok) echo json_encode(['status'=>'ok','msg'=>'sent']);
else { http_response_code(500); echo json_encode(['status'=>'error','msg'=>'mail failed']); }
