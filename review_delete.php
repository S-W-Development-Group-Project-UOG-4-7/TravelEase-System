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

$input = json_decode(file_get_contents('php://input'), true);
$reviewId = (int)($input['id'] ?? 0);

if ($reviewId <= 0) {
    echo json_encode(['ok' => false, 'message' => 'Invalid review.']);
    exit();
}

$userId = (int)$_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("DELETE FROM package_reviews WHERE id = :rid AND user_id = :uid");
    $stmt->execute([':rid' => $reviewId, ':uid' => $userId]);

    if ($stmt->rowCount() === 0) {
        echo json_encode(['ok' => false, 'message' => 'Review not found or not allowed.']);
        exit();
    }

    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
