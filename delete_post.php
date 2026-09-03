<?php

require_once __DIR__ . "/auth.php";
<<<<<<< HEAD
require_once __DIR__ . "/page.php";
require_once __DIR__ . "/post_images.php";

$mysqli = require __DIR__ . "/database.php";
$user = require_login($mysqli);
=======
require_login();

>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
$userId = (int) $user["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf_token();
}

<<<<<<< HEAD
=======
// Check ownership both when opening the page and when confirming deletion.
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
$row = get_owned_post($mysqli, $userId);
$id = (int) $row["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
<<<<<<< HEAD
    try {
        $stmt = $mysqli->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
    } catch (mysqli_sql_exception $error) {
        flash("Julkaisua ei voitu poistaa. Yritä uudelleen.", "error");
        redirect_to("delete_post.php?id=" . $id);
    }

    if ($stmt->affected_rows === 1) {
        remove_unused_post_image($mysqli, $row["image"]);
        flash("Julkaisu poistettiin.");
    } else {
        flash("Julkaisua ei enää löytynyt.", "error");
    }

    redirect_to("index.php");
}

render_page_start("Poista julkaisu", $user);
?>

<section class="card form-card compact-card">
    <h1>Poista julkaisu</h1>
    <p>Haluatko varmasti poistaa tämän julkaisun? Poistoa ei voi perua.</p>
    <p class="car-name"><?= e($row["brand"] . " " . $row["model"]) ?></p>
    <p class="post-content"><?= e($row["content"]) ?></p>

    <form action="delete_post.php?id=<?= $id ?>" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="actions">
            <button class="button button-danger" type="submit">Poista julkaisu</button>
            <a class="button button-secondary" href="index.php">Peruuta</a>
        </div>
    </form>
</section>

<?php render_page_end(); ?>
=======
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
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
