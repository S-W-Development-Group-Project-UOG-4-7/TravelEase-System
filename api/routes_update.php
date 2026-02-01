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
$name = isset($input['name']) ? trim((string)$input['name']) : null;
$geojson = $input['geojson'] ?? null;

if ($id <= 0 || ($name === null && $geojson === null)) {
  echo json_encode(['ok' => false, 'error' => 'Missing route data']);
  exit;
}

$fields = [];
$params = [':id' => $id, ':uid' => (int)$_SESSION['user_id']];

if ($name !== null) {
  if ($name === '') {
    echo json_encode(['ok' => false, 'error' => 'Route name required']);
    exit;
  }
  $fields[] = "name = :name";
  $params[':name'] = $name;
}

if ($geojson !== null) {
  $fields[] = "geojson = :geojson::jsonb";
  $params[':geojson'] = json_encode($geojson);
}

$sql = "UPDATE user_routes SET " . implode(', ', $fields) . " WHERE id = :id AND user_id = :uid";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

if ($stmt->rowCount() === 0) {
  echo json_encode(['ok' => false, 'error' => 'Route not found']);
  exit;
}

echo json_encode(['ok' => true]);
