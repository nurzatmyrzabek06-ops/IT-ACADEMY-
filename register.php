<?php
// ============================
// register.php — Тіркелу
// ============================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'POST сұраныс керек']);
    exit;
}

$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name']  ?? '');
$email      = trim($_POST['email']      ?? '');
$phone      = trim($_POST['phone']      ?? '');
$role       = trim($_POST['role']       ?? 'student');
$password   = $_POST['password']        ?? '';
$avatar     = trim($_POST['avatar']     ?? '🧑‍💻');

// Validation
if (!$first_name || !$last_name) {
    echo json_encode(['success' => false, 'message' => 'Аты-жөні міндетті']); exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email форматы дұрыс емес']); exit;
}
if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Құпия сөз кемінде 6 символ']); exit;
}

$pdo = getDB();

// Email бар ма тексеру
$check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$check->execute([$email]);
if ($check->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Бұл email тіркелген']); exit;
}

// Пароль хэштеу (қауіпсіз!)
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Деректерді сақтау
$stmt = $pdo->prepare("
    INSERT INTO users (first_name, last_name, email, phone, role, password, avatar, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
");
$stmt->execute([$first_name, $last_name, $email, $phone, $role, $hashed, $avatar]);

echo json_encode([
    'success' => true,
    'message' => 'Аккаунт сәтті жасалды',
    'user' => [
        'id'         => $pdo->lastInsertId(),
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'email'      => $email,
        'phone'      => $phone,
        'role'       => $role,
        'avatar'     => $avatar,
        'created_at' => date('d.m.Y'),
    ]
]);
