<?php
require "db.php";

$users = $pdo->query("SELECT id, username FROM users")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($users);
