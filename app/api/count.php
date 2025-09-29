<?php
header('Content-Type: application/json');
require __DIR__.'/../inc/db.php';
$cnt = (int)$pdo->query("SELECT COUNT(*) FROM issues")->fetchColumn();
echo json_encode(['count'=>$cnt]);
