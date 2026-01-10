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

$userId = (int)$_SESSION['user_id'];

$packageId  = (int)($_POST['package_id'] ?? 0);
$rating     = (int)($_POST['rating'] ?? 0);
$reviewText = trim($_POST['review_text'] ?? '');

if ($packageId <= 0) {
    echo json_encode(['ok' => false, 'message' => 'Invalid package.']);
    exit();
}
if ($rating < 1 || $rating > 5) {
    echo json_encode(['ok' => false, 'message' => 'Rating must be between 1 and 5.']);
    exit();
}
if ($reviewText === '' || mb_strlen($reviewText) < 5) {
    echo json_encode(['ok' => false, 'message' => 'Review must be at least 5 characters.']);
    exit();
}
if (mb_strlen($reviewText) > 1000) {
    echo json_encode(['ok' => false, 'message' => 'Review is too long (max 1000 chars).']);
    exit();
}

try {
    // Ensure user booked this package
    $stmt = $pdo->prepare("
        SELECT 1
        FROM trips
        WHERE user_id = :uid AND package_id = :pid
        LIMIT 1
    ");
    $stmt->execute([':uid' => $userId, ':pid' => $packageId]);

    if (!$stmt->fetchColumn()) {
        echo json_encode(['ok' => false, 'message' => 'You can only review packages you booked.']);
        exit();
    }

    // Insert review (PostgreSQL)
    $stmt = $pdo->prepare("
        INSERT INTO package_reviews (user_id, package_id, rating, review_text)
        VALUES (:uid, :pid, :rating, :text)
        RETURNING id, created_at
    ");
    $stmt->execute([
        ':uid'    => $userId,
        ':pid'    => $packageId,
        ':rating' => $rating,
        ':text'   => $reviewText
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch inserted review details for UI
    $stmt = $pdo->prepare("
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
        WHERE r.id = :rid
        LIMIT 1
    ");
    $stmt->execute([':rid' => (int)$row['id']]);
    $review = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'ok' => true,
        'message' => 'Review submitted successfully!',
        'review' => $review
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
