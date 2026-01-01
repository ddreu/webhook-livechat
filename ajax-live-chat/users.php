<?php
require "db.php";
header("Content-Type: application/json");

$stmt = $pdo->query("SELECT username FROM users ORDER BY username ASC");
echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
