<?php
// ============================
// send_otp.php — Email арқылы OTP жіберу
// ============================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'database.php';
require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/SMTP.php';
require_once 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'POST сұраныс керек']);
    exit;
}

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email форматы дұрыс емес']);
    exit;
}

$pdo = getDB();

// Email тіркелген бе?
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Бұл email тіркелмеген']);
    exit;
}

// 6 санды OTP жасау
$otp = strval(rand(100000, 999999));
date_default_timezone_set('Asia/Almaty');
$expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));

// OTP-ні базаға сақтау
$pdo->prepare("DELETE FROM otp_codes WHERE email = ?")->execute([$email]);
$pdo->prepare("INSERT INTO otp_codes (email, otp, expires_at) VALUES (?, ?, ?)")
    ->execute([$email, $otp, $expires]);

// Email жіберу (Gmail SMTP)
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'nurzatmyrzabek06@gmail.com';   // ← өз emailіңіз
    $mail->Password   = 'nukx ltlz fnjf vxsv';         // ← App Password (төменде түсіндіремін)
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('nurzatmyrzabek06@gmail.com', 'IT Academy');
    $mail->addAddress($email);
    $mail->Subject = 'IT Academy — Құпия сөзді қалпына келтіру коды';
    $mail->isHTML(true);
    $mail->Body = "
        <div style='font-family:sans-serif;max-width:400px;margin:auto;padding:30px;background:#f9f9f9;border-radius:12px;'>
            <h2 style='color:#6366f1;'>IT Academy 🎓</h2>
            <p>Құпия сөзді қалпына келтіру коды:</p>
            <div style='font-size:36px;font-weight:bold;letter-spacing:10px;color:#302b63;text-align:center;padding:20px;background:white;border-radius:8px;margin:20px 0;'>
                $otp
            </div>
            <p style='color:#888;font-size:13px;'>Код 10 минут ішінде жарамды.<br>Егер сіз сұратпасаңыз — бұл хатты елемеңіз.</p>
        </div>
    ";

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Код жіберілді']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Email жіберілмеді: ' . $mail->ErrorInfo]);
}
