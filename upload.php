<?php

require_once __DIR__ . "/auth.php";
require_login();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: create_post.php");
    exit;
}

verify_csrf_token();

// The account ID and author name always come from the logged-in account.
$userId = (int) $user["id"];
$author = $user["name"];
$brand = required_post_text("brand");
$model = required_post_text("model");
$content = required_post_text("content");

require_once __DIR__ . "/post_images.php";
$imagePath = save_post_image();

$sql = "INSERT INTO posts (user_id, author, brand, model, content, image)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("isssss", $userId, $author, $brand, $model, $content, $imagePath);
$stmt->execute();

header("Location: index.php");
exit;
