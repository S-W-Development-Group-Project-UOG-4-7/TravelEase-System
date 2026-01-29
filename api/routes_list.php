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

$stmt = $pdo->prepare("SELECT id, name, geojson, created_at FROM user_routes WHERE user_id = :uid ORDER BY id DESC");
$stmt->execute([':uid' => $userId]);

$rows = $stmt->fetchAll();
foreach ($rows as &$r) {
  // ensure JSONB comes as object
  if (is_string($r['geojson'])) {
    $r['geojson'] = json_decode($r['geojson'], true);
  }
}
echo json_encode($rows);
