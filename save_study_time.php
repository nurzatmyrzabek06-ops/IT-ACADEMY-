<?php
// save_study_time.php — Оқу уақытын сақтау
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]); exit;
}

$user_id = intval($_POST['user_id'] ?? 0);
$seconds = intval($_POST['seconds'] ?? 0);
$minutes = intval($_POST['minutes'] ?? 0);
if ($seconds <= 0 && $minutes > 0) $seconds = $minutes * 60;
if (!$user_id || $seconds <= 0) { echo json_encode(['success' => false]); exit; }

$pdo = getDB();
$today = date('Y-m-d');

// Бүгінгі уақытты жаңарт немесе жаңасын қос
$pdo->prepare("
    INSERT INTO study_time (user_id, study_date, minutes)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE minutes = minutes + ?
")->execute([$user_id, $today, $seconds, $seconds]);

echo json_encode(['success' => true]);
