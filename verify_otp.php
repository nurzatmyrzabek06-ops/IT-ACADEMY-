<?php
// ============================
// verify_otp.php — OTP тексеру
// ============================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'database.php';

$email = trim($_POST['email'] ?? '');
$otp   = trim($_POST['otp']   ?? '');

if (!$email || !$otp) {
    echo json_encode(['success' => false, 'message' => 'Email және код міндетті']);
    exit;
}

$pdo = getDB();

$stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE email = ? AND otp = ? AND expires_at > NOW()");
$stmt->execute([$email, $otp]);
$row = $stmt->fetch();

if (!$row) {
    echo json_encode(['success' => false, 'message' => 'Код қате немесе мерзімі өтті']);
    exit;
}

echo json_encode(['success' => true]);
