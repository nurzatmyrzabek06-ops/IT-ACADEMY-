<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]); exit;
}

$user_id = (int)($_POST['user_id'] ?? 0);
$lesson  = (int)($_POST['lesson']  ?? 0);
$course  = 'python';

if (!$user_id || $lesson < 1 || $lesson > 15) {
    echo json_encode(['success' => false, 'message' => 'Қате деректер']); exit;
}

$pdo = getDB();
$stmt = $pdo->prepare("SELECT max_lesson FROM progress WHERE user_id=? AND course=?");
$stmt->execute([$user_id, $course]);
$row = $stmt->fetch();

if ($row) {
    if ($lesson > (int)$row['max_lesson']) {
        $pdo->prepare("UPDATE progress SET max_lesson=? WHERE user_id=? AND course=?")
            ->execute([$lesson, $user_id, $course]);
    }
} else {
    $pdo->prepare("INSERT INTO progress (user_id, course, max_lesson) VALUES (?,?,?)")
        ->execute([$user_id, $course, $lesson]);
}

echo json_encode(['success' => true]);
