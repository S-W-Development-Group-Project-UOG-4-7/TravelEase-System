<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['ok' => false, 'error' => 'Not logged in']);
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$pinId = (int)($input['id'] ?? 0);

if ($pinId <= 0) {
  echo json_encode(['ok' => false, 'error' => 'Invalid pin id']);
  exit;
}

$userId = (int)$_SESSION['user_id'];

// Only delete if belongs to this user
$stmt = $pdo->prepare("DELETE FROM user_pins WHERE id = :id AND user_id = :uid");
$stmt->execute([':id' => $pinId, ':uid' => $userId]);

echo json_encode(['ok' => true]);
