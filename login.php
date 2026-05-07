<?php
// login.php — Кіру + session
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'POST сұраныс керек']);
    exit;
}

$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']      ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Email және пароль міндетті']); exit;
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Email немесе пароль қате']); exit;
}

// Session сақтау
$_SESSION['user_id']    = $user['id'];
$_SESSION['user_email'] = $user['email'];

$pdo->prepare("INSERT INTO user_logins (user_id, login_date) VALUES (?, NOW())")->execute([$user['id']]);

echo json_encode([
    'success' => true,
    'user' => [
        'id'         => $user['id'],
        'first_name' => $user['first_name'],
        'last_name'  => $user['last_name'],
        'email'      => $user['email'],
        'phone'      => $user['phone'],
        'role'       => $user['role'],
        'avatar'     => $user['avatar'],
        'created_at' => date('d.m.Y', strtotime($user['created_at'])),
    ]
]);
