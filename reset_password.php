<?php
// ============================
// reset_password.php — Пароль жаңарту (email арқылы)
// ============================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'POST сұраныс керек']);
    exit;
}

$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']      ?? '';

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Email міндетті']); exit;
}
if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Құпия сөз кемінде 6 символ']); exit;
}

$pdo = getDB();

$check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$check->execute([$email]);
if (!$check->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Email табылмады']); exit;
}

$hashed = password_hash($password, PASSWORD_BCRYPT);
$pdo->prepare("UPDATE users SET password = ? WHERE email = ?")->execute([$hashed, $email]);
$pdo->prepare("DELETE FROM otp_codes WHERE email = ?")->execute([$email]);

echo json_encode(['success' => true, 'message' => 'Құпия сөз сәтті өзгертілді']);
