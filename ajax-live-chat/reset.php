<?php

// Include your DB connection
require "db.php";


// Clear all messages
$pdo->exec("TRUNCATE TABLE messages"); // deletes all messages and resets AUTO_INCREMENT

// Return JSON response
echo json_encode(["status" => "ok"]);
