<?php

require_once __DIR__ . "/auth.php";
require_login();

$userId = (int) $user["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf_token();
}

// Check ownership both when opening the page and when confirming deletion.
$row = get_owned_post($mysqli, $userId);
$id = (int) $row["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $mysqli->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $userId);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
        require_once __DIR__ . "/post_images.php";
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

<div id="post_delete_background">

<form id="post_delete" method="POST">

    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

    <input
        id="delete_input"
        type="hidden"
        name="id"
        value="<?php echo htmlspecialchars($id); ?>"
    >

    <p id="delete_p">
        Haluatko varmasti poistaa julkaisun?
    </p>

    <button id="delete_submit" type="submit">
        Poista
    </button>

</form>

</div>

</body>
</html>
