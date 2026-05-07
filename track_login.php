<?php
// track_login.php — Кіру санын тіркеу (тек шын login кезінде)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]); exit;
}

$user_id = intval($_POST['user_id'] ?? 0);
if (!$user_id) { echo json_encode(['success' => false]); exit; }

$pdo = getDB();

// Бүгінгі күн
$today = date('Y-m-d');

// Кіру санын +1 қос (күніне бір рет немесе әр login)
$pdo->prepare("
    INSERT INTO user_logins (user_id, login_date)
    VALUES (?, NOW())
")->execute([$user_id]);

// Жауап: жалпы кіру саны
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM user_logins WHERE user_id = ?");
$stmt->execute([$user_id]);
$row = $stmt->fetch();

echo json_encode(['success' => true, 'total_logins' => $row['total']]);
