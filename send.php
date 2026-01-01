<?php
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$senderName   = trim($data["sender"]);
$receiverName = trim($data["receiver"]);
$message      = trim($data["message"]);

if (!$senderName || !$receiverName || !$message) {
    http_response_code(400);
    exit;
}

// Resolve usernames to IDs
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$senderName]);
$senderId = $stmt->fetchColumn();

$stmt->execute([$receiverName]);
$receiverId = $stmt->fetchColumn();

if (!$senderId || !$receiverId) {
    http_response_code(404);
    echo "User not found";
    exit;
}

// Save message
$stmt = $pdo->prepare(
    "INSERT INTO messages (sender_id, receiver_id, message)
     VALUES (?, ?, ?)"
);

$stmt->execute([$senderId, $receiverId, htmlspecialchars($message)]);

echo "OK";
