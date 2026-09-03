<?php

require_once __DIR__ . "/auth.php";
<<<<<<< HEAD
require_once __DIR__ . "/post_images.php";

$mysqli = require __DIR__ . "/database.php";
$user = require_login($mysqli);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_to("create_post.php");
}

verify_csrf_token();

$userId = (int) $user["id"];
$author = $user["name"];
$values = [
    "brand" => post_text("brand"),
    "model" => post_text("model"),
    "content" => post_text("content")
];
$imagePath = "";

try {
    $brand = required_post_text("brand");
    $model = required_post_text("model");
    $content = required_post_text("content");
    $imagePath = save_post_image();

    $stmt = $mysqli->prepare(
        "INSERT INTO posts (user_id, author, brand, model, content, image) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("isssss", $userId, $author, $brand, $model, $content, $imagePath);
    $stmt->execute();
} catch (mysqli_sql_exception $error) {
    discard_uploaded_image($imagePath);
    $_SESSION["forms"]["create"] = $values;
    flash("Tallentaminen epäonnistui. Yritä uudelleen ja valitse kuva uudestaan.", "error");
    redirect_to("create_post.php");
} catch (RuntimeException | InvalidArgumentException $error) {
    $_SESSION["forms"]["create"] = $values;
    flash($error->getMessage() . " Valitse mahdollinen kuva uudelleen.", "error");
    redirect_to("create_post.php");
}

unset($_SESSION["forms"]["create"]);
flash("Julkaisu lisättiin.");
redirect_to("index.php");
=======
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
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
