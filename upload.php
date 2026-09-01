
<?php

session_start();

include "database.php";

$author = $_POST["author"];
$brand = $_POST["brand"];
$model = $_POST["model"];
$content = $_POST["content"];

$imagePath = "";

if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {

    $uploadDir = "kuvat/";

    // Make sure the folder exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Get file extension
    $extension = strtolower(
        pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
    );

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES["image"]["tmp_name"]);
    finfo_close($finfo);

    $allowedTypes = [
    "image/jpeg",
    "image/png",
    "image/gif",
    "image/webp"
    ];

if (!in_array($mime, $allowedTypes)) {
    die("Invalid image type.");
}


    // Create a unique filename
    $filename = uniqid() . "." . $extension;

    $imagePath = $uploadDir . $filename;

    // Move uploaded file into kuvat folder
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
        die("Failed to upload image.");
    }
}

// Insert post into database
$sql = "INSERT INTO posts (author, brand, model, content, image)
        VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($mysqli, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssss",
    $author,
    $brand,
    $model,
    $content,
    $imagePath
);

mysqli_stmt_execute($stmt);

echo "Post uploaded successfully!";

header("Location: index.php");
exit();

?>