<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/page.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf_token();
    $_SESSION = [];
    session_regenerate_id(true);
    flash("Olet kirjautunut ulos.");
    redirect_to("index.php");
}

$mysqli = require __DIR__ . "/database.php";
$user = get_current_user($mysqli);

if ($user === null) {
    redirect_to("index.php");
}

// Old bookmarks still lead to a usable confirmation page.
render_page_start("Kirjaudu ulos", $user);
?>
<section class="card form-card compact-card">
    <h1>Kirjaudu ulos</h1>
    <p>Haluatko kirjautua ulos?</p>
    <form action="logout.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <div class="actions">
            <button class="button" type="submit">Kirjaudu ulos</button>
            <a class="button button-secondary" href="index.php">Peruuta</a>
        </div>
    </form>
</section>
<?php render_page_end(); ?>
