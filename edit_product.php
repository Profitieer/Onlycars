<?php

require_once __DIR__ . "/auth.php";
require_login();

$userId = (int) $user["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf_token();
}

// Check ownership before showing the form or processing any uploaded image.
$row = get_owned_post($mysqli, $userId);
$id = (int) $row["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $brand = required_post_text("brand");
    $model = required_post_text("model");
    $content = required_post_text("content");

    require_once __DIR__ . "/post_images.php";
    $imagePath = save_post_image($row["image"] ?? "");

    // Keep the ownership condition in the UPDATE itself as well.
    $sql = "UPDATE posts
            SET brand = ?, model = ?, content = ?, image = ?
            WHERE id = ? AND user_id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssssii", $brand, $model, $content, $imagePath, $id, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0 && $imagePath !== $row["image"]) {
        remove_unused_post_image($mysqli, $row["image"]);
    }

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Media</title>
    <link href="styles2.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitcount+Prop+Single:wght@100..900&family=Caveat:wght@400..700&display=swap" rel="stylesheet">
</head>
<body>


<?php include "header.php"; ?>

<div id="edit_post_background">

<form id="post_edit" method="post" enctype="multipart/form-data">

<h1>Julkaisun muokkaus</h1>

    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

    Nimi<input
        class="author"
        type="text"
        readonly
        value="<?php echo htmlspecialchars($row["author"]); ?>"
    >

    <br><br>

    Merkki<input
        class="brand"
        type="text"
        name="brand"
        required
        value="<?php echo htmlspecialchars($row["brand"]); ?>"
    >

    <br><br>

    Malli<input
        class="model"
        type="text"
        name="model"
        required
        value="<?php echo htmlspecialchars($row["model"]); ?>"
    >

    <br><br>

    Julkaisu<textarea
        class="content"
        name="content"
        required
    ><?php echo htmlspecialchars($row["content"]); ?></textarea>

    <br><br>

    <p>Nykyinen kuva:</p>

    <?php if (!empty($row["image"])): ?>

        <img
            src="<?php echo htmlspecialchars($row["image"]); ?>"
            alt="Nykyinen kuva"
            width="auto"
            height="200"
        >

    <?php else: ?>

        <p>Ei kuvaa.</p>

    <?php endif; ?>

    <p>Vaihda kuva:</p>

    <input
        type="file"
        name="image"
        accept="image/jpeg,image/png,image/gif,image/webp"
    >

    <input
        type="hidden"
        name="id"
        value="<?php echo $row["id"]; ?>"
    >

    <br><br>

    <input
        class="submit"
        type="submit"
        value="Päivitä"
        id="päivitä"
    >

</form>

</div>

</body>
</html>
