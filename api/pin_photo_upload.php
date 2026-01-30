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
$pinId = (int)($_POST['pin_id'] ?? 0);
$caption = trim($_POST['caption'] ?? '');

if ($pinId <= 0) {
  echo json_encode(['ok' => false, 'error' => 'Invalid pin id']);
  exit;
}

// Verify pin belongs to user
$chk = $pdo->prepare("SELECT id FROM user_pins WHERE id = :pid AND user_id = :uid");
$chk->execute([':pid' => $pinId, ':uid' => $userId]);
if (!$chk->fetch()) {
  echo json_encode(['ok' => false, 'error' => 'Not allowed']);
  exit;
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(['ok' => false, 'error' => 'No photo uploaded']);
  exit;
}

$file = $_FILES['photo'];
$maxSize = 5 * 1024 * 1024; // 5MB
if ($file['size'] > $maxSize) {
  echo json_encode(['ok' => false, 'error' => 'File too large (max 5MB)']);
  exit;
}

// Validate extension + mime
$allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt, true)) {
  echo json_encode(['ok' => false, 'error' => 'Only JPG/PNG/WEBP allowed']);
  exit;
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']);
$allowedMime = ['image/jpeg', 'image/png', 'image/webp'];
if (!in_array($mime, $allowedMime, true)) {
  echo json_encode(['ok' => false, 'error' => 'Invalid image type']);
  exit;
}

// Save with random name
$uploadDir = realpath(__DIR__ . '/../uploads/pin_photos');
if ($uploadDir === false) {
  echo json_encode(['ok' => false, 'error' => 'Upload folder missing']);
  exit;
}

$newName = bin2hex(random_bytes(16)) . "." . $ext;
$targetPath = $uploadDir . DIRECTORY_SEPARATOR . $newName;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
  echo json_encode(['ok' => false, 'error' => 'Failed to save file']);
  exit;
}

// Store in DB
$stmt = $pdo->prepare("
  INSERT INTO pin_photos (pin_id, file_name, caption)
  VALUES (:pid, :file, :caption)
  RETURNING id
");
$stmt->execute([
  ':pid' => $pinId,
  ':file' => $newName,
  ':caption' => $caption
]);

echo json_encode(['ok' => true, 'photo_id' => (int)$stmt->fetchColumn()]);
