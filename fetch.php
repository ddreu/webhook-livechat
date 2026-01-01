<?php
require "db.php";

$me = $_GET['me'];
$them = $_GET['them'];

$stmt = $pdo->prepare("
    SELECT m.sender_id, m.receiver_id, u.username AS sender_name, m.message, m.created_at
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE (m.sender_id = ? AND m.receiver_id = ?)
       OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.created_at ASC
");
$stmt->execute([$me, $them, $them, $me]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);
