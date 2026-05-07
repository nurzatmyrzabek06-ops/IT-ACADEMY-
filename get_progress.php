<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once 'database.php';

$user_id = (int)($_GET['user_id'] ?? 0);
$course  = 'python';

if (!$user_id) {
    echo json_encode(['success' => true, 'max_lesson' => 1]); exit;
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT max_lesson FROM progress WHERE user_id=? AND course=?");
$stmt->execute([$user_id, $course]);
$row = $stmt->fetch();

echo json_encode([
    'success'    => true,
    'max_lesson' => $row ? (int)$row['max_lesson'] : 1
]);
