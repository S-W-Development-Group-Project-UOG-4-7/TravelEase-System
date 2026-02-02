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
$title = trim($input['title'] ?? '');
$notes = trim($input['notes'] ?? '');
$lat   = $input['lat'] ?? null;
$lng   = $input['lng'] ?? null;

if ($title === '' || $lat === null || $lng === null) {
  echo json_encode(['ok' => false, 'error' => 'Missing required fields']);
  exit;
}

$userId = (int)$_SESSION['user_id'];

$stmt = $pdo->prepare("
  INSERT INTO user_pins (user_id, title, notes, lat, lng)
  VALUES (:uid, :title, :notes, :lat, :lng)
  RETURNING id
");
$stmt->execute([
  ':uid' => $userId,
  ':title' => $title,
  ':notes' => $notes,
  ':lat' => $lat,
  ':lng' => $lng
]);

$newId = $stmt->fetchColumn();
echo json_encode(['ok' => true, 'id' => (int)$newId]);
