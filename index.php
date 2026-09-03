<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/page.php";

$mysqli = require __DIR__ . "/database.php";
$user = get_current_user($mysqli);
$result = $mysqli->query("SELECT * FROM posts ORDER BY created_at DESC");

render_page_start("Etusivu", $user, "home");
?>

<div class="page-heading">
    <div>
        <h1>Uusimmat julkaisut</h1>
        <p class="intro">Katso muiden autot ja jaa omasi.</p>
    </div>
    <a class="button" href="create_post.php">Luo julkaisu</a>
</div>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <article class="card post">
            <h2><?= e($row["author"]) ?></h2>
            <p class="car-name"><?= e($row["brand"] . " " . $row["model"]) ?></p>

            <?php if (!empty($row["image"])): ?>
                <img class="post-image" src="<?= e($row["image"]) ?>" alt="<?= e($row["brand"] . " " . $row["model"]) ?>" loading="lazy">
            <?php endif; ?>

            <p class="post-content"><?= e($row["content"]) ?></p>
            <small class="post-date"><?= e($row["created_at"]) ?></small>

            <?php if ($user !== null && (int) $user["id"] === (int) ($row["user_id"] ?? 0)): ?>
                <div class="actions">
                    <a class="button button-secondary" href="edit_product.php?id=<?= (int) $row["id"] ?>">Muokkaa</a>
                    <a class="button button-secondary" href="delete_post.php?id=<?= (int) $row["id"] ?>">Poista</a>
                </div>
            <?php endif; ?>
        </article>
    <?php endwhile; ?>
<?php else: ?>
    <section class="card">
        <h2>Ei vielä julkaisuja</h2>
        <p>Ole ensimmäinen ja jaa oma autosi.</p>
    </section>
<?php endif; ?>

<?php render_page_end(); ?>
