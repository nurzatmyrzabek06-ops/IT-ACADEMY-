<?php
// get_stats.php — Статистика: кіру саны, оқу уақыты, белсенді күндер
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once 'database.php';

$user_id = intval($_GET['user_id'] ?? 0);
if (!$user_id) { echo json_encode(['success' => false]); exit; }

$pdo = getDB();

// Жалпы кіру саны
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM user_logins WHERE user_id = ?");
$stmt->execute([$user_id]);
$logins = $stmt->fetch()['total'];

// Жалпы оқу уақыты (секунд -> минутқа айналдыру)
$stmt = $pdo->prepare("SELECT COALESCE(SUM(minutes), 0) as total FROM study_time WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_secs = $stmt->fetch()['total'];
$total_mins = round($total_secs / 60);

// Белсенді күндер (оқыған күндер тізімі)
$stmt = $pdo->prepare("SELECT study_date FROM study_time WHERE user_id = ? AND minutes > 0 ORDER BY study_date");
$stmt->execute([$user_id]);
$active_days = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode([
    'success' => true,
    'total_logins' => intval($logins),
    'total_minutes' => intval($total_mins),
    'total_seconds' => intval($total_secs),
    'active_days' => $active_days
]);
