<?php
require "db.php";

$me   = trim($_GET["me"]);
$them = trim($_GET["them"]);

$stmt = $pdo->prepare("
    SELECT 
      u1.username AS sender,
      u2.username AS receiver,
      m.message,
      m.created_at
    FROM messages m
    JOIN users u1 ON m.sender_id = u1.id
    JOIN users u2 ON m.receiver_id = u2.id
    WHERE
      (u1.username = ? AND u2.username = ?)
      OR
      (u1.username = ? AND u2.username = ?)
    ORDER BY m.created_at ASC
");

$stmt->execute([$me, $them, $them, $me]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
