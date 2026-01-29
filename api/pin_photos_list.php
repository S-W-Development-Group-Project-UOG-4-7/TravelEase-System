<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['ok' => false, 'error' => 'Not logged in']);
  exit;
}

$userId = (int)$_SESSION['user_id'];
$pinId = (int)($_GET['pin_id'] ?? 0);

if ($pinId <= 0) {
  echo json_encode(['ok' => false, 'error' => 'Invalid pin id']);
  exit;
}

// verify ownership
$chk = $pdo->prepare("SELECT id FROM user_pins WHERE id = :pid AND user_id = :uid");
$chk->execute([':pid' => $pinId, ':uid' => $userId]);
if (!$chk->fetch()) {
  echo json_encode(['ok' => false, 'error' => 'Not allowed']);
  exit;
}

$stmt = $pdo->prepare("SELECT id, file_name, caption, uploaded_at FROM pin_photos WHERE pin_id = :pid ORDER BY id DESC");
$stmt->execute([':pid' => $pinId]);

echo json_encode(['ok' => true, 'photos' => $stmt->fetchAll()]);
