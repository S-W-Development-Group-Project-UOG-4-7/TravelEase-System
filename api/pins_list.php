<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode([]);
  exit;
}

$userId = (int)$_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT id, title, notes, lat, lng, created_at FROM user_pins WHERE user_id = :uid ORDER BY id DESC");
$stmt->execute([':uid' => $userId]);

echo json_encode($stmt->fetchAll());
