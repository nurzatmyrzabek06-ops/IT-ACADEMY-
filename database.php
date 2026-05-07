<?php
// ============================
// database.php — DB қосылым
// MAMP үшін: localhost:8889
// XAMPP үшін: localhost:3306
// ============================

define('DB_HOST', 'localhost');
define('DB_PORT', '8889');      // MAMP=8889, XAMPP=3306
define('DB_NAME', 'it_academy');
define('DB_USER', 'root');
define('DB_PASS', 'root');      // MAMP default = root

function getDB() {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'DB қосылым қатесі: ' . $e->getMessage()]);
        exit;
    }
}
