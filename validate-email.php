<?php

$mysqli = require __DIR__ . "/database.php";

$email = $_GET["email"] ?? "";

$stmt = $mysqli->prepare(
    "SELECT id FROM user WHERE email = ?"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

$is_available = $result->num_rows === 0;

header("Content-Type: application/json");

echo json_encode([
    "available" => $is_available
]);

?>