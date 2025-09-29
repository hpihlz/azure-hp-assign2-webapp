<?php
header('Content-Type: application/json');
require __DIR__.'/../inc/db.php';
$stmt = $pdo->query("SELECT id, created_at, name, email, description, (screenshot IS NOT NULL) AS has_screenshot FROM issues ORDER BY id DESC");
echo json_encode($stmt->fetchAll());
