<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/page.php";
require_once __DIR__ . "/post_form.php";

$mysqli = require __DIR__ . "/database.php";
$user = require_login($mysqli);
$values = take_form_values("create");

render_page_start("Luo julkaisu", $user, "create");
?>

<section class="card form-card">
    <h1>Luo julkaisu</h1>
    <p class="intro">Jaa autosi ja sen tarina.</p>
    <?php render_post_form($values, $user["name"]); ?>
</section>

<?php render_page_end(); ?>
