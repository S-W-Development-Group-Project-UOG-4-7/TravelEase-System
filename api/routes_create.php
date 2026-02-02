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
$name = trim($input['name'] ?? '');
$geojson = $input['geojson'] ?? null;

if ($name === '' || !$geojson) {
  echo json_encode(['ok' => false, 'error' => 'Missing route data']);
  exit;
}

$userId = (int)$_SESSION['user_id'];

$stmt = $pdo->prepare("
  INSERT INTO user_routes (user_id, name, geojson)
  VALUES (:uid, :name, :geojson::jsonb)
  RETURNING id
");
$stmt->execute([
  ':uid' => $userId,
  ':name' => $name,
  ':geojson' => json_encode($geojson)
]);

$newId = $stmt->fetchColumn();
echo json_encode(['ok' => true, 'id' => (int)$newId]);
