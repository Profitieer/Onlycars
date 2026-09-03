<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/page.php";
require_once __DIR__ . "/post_images.php";

$mysqli = require __DIR__ . "/database.php";
$user = require_login($mysqli);
$userId = (int) $user["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf_token();
}

$row = get_owned_post($mysqli, $userId);
$id = (int) $row["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
