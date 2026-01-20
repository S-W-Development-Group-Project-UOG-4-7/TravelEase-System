<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Asia/Colombo');
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Unauthorized']);
    exit();
}

$userId  = (int)$_SESSION['user_id'];
$sinceId = (int)($_GET['since_id'] ?? 0);

try {
    // Packages the user booked (to keep dashboard feed relevant)
    $stmt = $pdo->prepare("SELECT DISTINCT package_id FROM trips WHERE user_id = :uid");
    $stmt->execute([':uid' => $userId]);
    $pkgIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($pkgIds)) {
        echo json_encode(['ok' => true, 'reviews' => [], 'last_id' => $sinceId]);
        exit();
    }

    $placeholders = implode(',', array_fill(0, count($pkgIds), '?'));

    $sql = "
        SELECT
            r.id,
            r.rating,
            r.review_text,
            r.created_at,
            u.full_name AS reviewer_name,
            p.title AS package_title,
            c.name AS country_name
        FROM package_reviews r
        JOIN users u ON r.user_id = u.id
        JOIN packages p ON r.package_id = p.id
        JOIN countries c ON p.country_id = c.id
        WHERE r.package_id IN ($placeholders)
          AND r.id > ?
        ORDER BY r.id DESC
        LIMIT 10
    ";

    $params = array_map('intval', $pkgIds);
    $params[] = $sinceId;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $lastId = $sinceId;
    foreach ($reviews as $rv) {
        $lastId = max($lastId, (int)$rv['id']);
    }

    echo json_encode(['ok' => true, 'reviews' => $reviews, 'last_id' => $lastId]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
