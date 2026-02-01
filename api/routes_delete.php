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
$id = (int)($input['id'] ?? 0);

if ($id <= 0) {
  echo json_encode(['ok' => false, 'error' => 'Invalid route id']);
  exit;
}

$stmt = $pdo->prepare("DELETE FROM user_routes WHERE id = :id AND user_id = :uid");
$stmt->execute([':id' => $id, ':uid' => (int)$_SESSION['user_id']]);

if ($stmt->rowCount() === 0) {
  echo json_encode(['ok' => false, 'error' => 'Route not found']);
  exit;
}

echo json_encode(['ok' => true]);
