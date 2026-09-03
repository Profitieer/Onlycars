<?php

function save_post_image($currentPath = "")
{
    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] === UPLOAD_ERR_NO_FILE) {
        return $currentPath;
    }

    $image = $_FILES["image"];

    if ($image["error"] !== UPLOAD_ERR_OK) {
<<<<<<< HEAD
        throw new RuntimeException("Kuvan lähettäminen epäonnistui. Tarkista kuvan koko.");
=======
        http_response_code(400);
        exit("Kuvan lähettäminen epäonnistui. Tarkista kuvan koko.");
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
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
<<<<<<< HEAD
        throw new RuntimeException("Sallitut kuvatyypit ovat JPG, PNG, GIF ja WEBP.");
=======
        http_response_code(400);
        exit("Sallitut kuvatyypit ovat JPG, PNG, GIF ja WEBP.");
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
    }

    $uploadDir = __DIR__ . "/kuvat/";

<<<<<<< HEAD
    if (!is_dir($uploadDir) && !@mkdir($uploadDir, 0755, true)) {
        throw new RuntimeException("Kuvakansion luominen epäonnistui.");
=======
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        http_response_code(500);
        exit("Kuvakansion luominen epäonnistui.");
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
    }

    $filename = bin2hex(random_bytes(16)) . "." . $allowedTypes[$mime];

<<<<<<< HEAD
    if (!@move_uploaded_file($image["tmp_name"], $uploadDir . $filename)) {
        throw new RuntimeException("Kuvan tallentaminen epäonnistui.");
=======
    if (!move_uploaded_file($image["tmp_name"], $uploadDir . $filename)) {
        http_response_code(500);
        exit("Kuvan tallentaminen epäonnistui.");
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
    }

    return "kuvat/" . $filename;
}

<<<<<<< HEAD
function remove_unused_post_image(mysqli $mysqli, ?string $imagePath): void
=======
function remove_unused_post_image($mysqli, $imagePath)
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
{
    if (empty($imagePath)) {
        return;
    }

    // Keep images that are still used by another post.
<<<<<<< HEAD
    try {
        $stmt = $mysqli->prepare("SELECT id FROM posts WHERE image = ? LIMIT 1");
        $stmt->bind_param("s", $imagePath);
        $stmt->execute();

        if ($stmt->get_result()->fetch_assoc()) {
            return;
        }
    } catch (mysqli_sql_exception $error) {
        // The post change already succeeded. Keep the image if cleanup cannot be checked.
=======
    $stmt = $mysqli->prepare("SELECT id FROM posts WHERE image = ? LIMIT 1");
    $stmt->bind_param("s", $imagePath);
    $stmt->execute();

    if ($stmt->get_result()->fetch_assoc()) {
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
        return;
    }

    // Never delete files outside the upload folder.
    $uploadDir = realpath(__DIR__ . "/kuvat");
    $file = realpath(__DIR__ . "/" . $imagePath);

    if ($uploadDir !== false && $file !== false && dirname($file) === $uploadDir && is_file($file)) {
<<<<<<< HEAD
        @unlink($file);
    }
}


// Only use this for a newly generated upload whose database write failed.
function discard_uploaded_image(string $imagePath): void
{
    if (preg_match('~^kuvat/[a-f0-9]{32}\.(jpg|png|gif|webp)$~D', $imagePath)) {
        $file = __DIR__ . "/" . $imagePath;
        if (is_file($file)) {
            @unlink($file);
        }
=======
        unlink($file);
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
    }
}
