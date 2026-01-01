<?php
require "db.php";

$pdo->exec("TRUNCATE TABLE messages");
echo json_encode(["status" => "ok"]);
