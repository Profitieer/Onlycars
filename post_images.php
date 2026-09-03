<?php

function save_post_image($currentPath = "")
{
    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] === UPLOAD_ERR_NO_FILE) {
        return $currentPath;
    }

    $image = $_FILES["image"];

    if ($image["error"] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        exit("Kuvan lähettäminen epäonnistui. Tarkista kuvan koko.");
    }

    // Choose the extension from the actual file type, not the submitted name.
    $allowedTypes = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/gif" => "gif",
        "image/webp" => "webp"
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($image["tmp_name"]);

    if (!isset($allowedTypes[$mime]) || @getimagesize($image["tmp_name"]) === false) {
        http_response_code(400);
        exit("Sallitut kuvatyypit ovat JPG, PNG, GIF ja WEBP.");
    }

    $uploadDir = __DIR__ . "/kuvat/";

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        exit("Kuvakansion luominen epäonnistui.");
    }

    $filename = bin2hex(random_bytes(16)) . "." . $allowedTypes[$mime];

    if (!move_uploaded_file($image["tmp_name"], $uploadDir . $filename)) {
        http_response_code(500);
        exit("Kuvan tallentaminen epäonnistui.");
    }

    return "kuvat/" . $filename;
}

function remove_unused_post_image($mysqli, $imagePath)
{
    if (empty($imagePath)) {
        return;
    }

    // Keep images that are still used by another post.
    $stmt = $mysqli->prepare("SELECT id FROM posts WHERE image = ? LIMIT 1");
    $stmt->bind_param("s", $imagePath);
    $stmt->execute();

    if ($stmt->get_result()->fetch_assoc()) {
        return;
    }

    // Never delete files outside the upload folder.
    $uploadDir = realpath(__DIR__ . "/kuvat");
    $file = realpath(__DIR__ . "/" . $imagePath);

    if ($uploadDir !== false && $file !== false && dirname($file) === $uploadDir && is_file($file)) {
        unlink($file);
    }
}
