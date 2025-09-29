<?php
require __DIR__.'/../inc/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'], $_GET['download'])) {
  $id = (int)$_GET['id'];
  $stmt = $pdo->prepare("SELECT screenshot, screenshot_mime FROM issues WHERE id=?");
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  if (!$row || !$row['screenshot']) { http_response_code(404); exit; }
  header('Content-Type: '.$row['screenshot_mime']);
  echo $row['screenshot'];
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Invalid JSON']); exit; }

$name  = trim($input['name']  ?? '');
$email = trim($input['email'] ?? '');
$desc  = trim($input['description'] ?? '');
$mime  = trim($input['screenshot_mime'] ?? '');
$b64   = $input['screenshot_base64'] ?? null;

if ($name==='' || $email==='' || $desc==='') { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Missing fields']); exit; }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Invalid email']); exit; }

$blob = null;
if ($b64 && $mime) {
  if (!in_array($mime, ['image/png','image/jpeg','image/webp'])) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Unsupported image type']); exit; }
  $blob = base64_decode($b64, true);
  if ($blob === false || strlen($blob) > 6*1024*1024) { http_response_code(400); echo json_encode(['ok'=>false,'error'=>'Image too large or invalid']); exit; }
}

$stmt = $pdo->prepare("INSERT INTO issues (name,email,description,screenshot,screenshot_mime) VALUES (?,?,?,?,?)");
$stmt->bindParam(1, $name);
$stmt->bindParam(2, $email);
$stmt->bindParam(3, $desc);
$stmt->bindParam(4, $blob, PDO::PARAM_LOB);
$stmt->bindParam(5, $mime);
$stmt->execute();

echo json_encode(['ok'=>true, 'id'=>$pdo->lastInsertId()]);
