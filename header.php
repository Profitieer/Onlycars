<?php

require_once __DIR__ . "/helpers.php";

function render_header(?array $user, string $active = ""): void
{
    ?>
    <a class="skip-link" href="#main">Siirry sisältöön</a>
    <header class="site-header">
        <div class="header-panel">
            <a class="brand" href="index.php">OnlyCars</a>
            <p class="tagline">Autot, tarinat ja yhteinen intohimo.</p>
            <nav class="site-nav" aria-label="Päävalikko">
                <a href="index.php" <?= $active === "home" ? 'aria-current="page"' : "" ?>>Etusivu</a>
                <?php if ($user !== null): ?>
                    <a href="create_post.php" <?= $active === "create" ? 'aria-current="page"' : "" ?>>Luo julkaisu</a>
                    <form action="logout.php" method="post" class="logout-form">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <button type="submit">Kirjaudu ulos</button>
                    </form>
                <?php else: ?>
                    <a href="login.php" <?= $active === "login" ? 'aria-current="page"' : "" ?>>Kirjaudu sisään</a>
                    <a href="signup.php" <?= $active === "signup" ? 'aria-current="page"' : "" ?>>Luo tili</a>
                <?php endif; ?>
            </nav>
            <?php if ($user !== null): ?>
                <p class="account-name">Kirjautuneena: <?= e($user["name"]) ?></p>
            <?php endif; ?>
        </div>
    </header>
    <?php
}
