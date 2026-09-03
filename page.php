<?php

require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/header.php";

function render_page_start(string $title, ?array $user, string $active = ""): void
{
    ?>
    <!DOCTYPE html>
    <html lang="fi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= e($title) ?> | OnlyCars</title>
        <link rel="stylesheet" href="styles2.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bitcount+Prop+Single:wght@400;600&family=Caveat:wght@400;700&display=swap" rel="stylesheet">
    </head>
    <body>
    <?php render_header($user, $active); ?>
    <main id="main" class="page-shell">
        <?php if (isset($_SESSION["flash"])): ?>
            <?php
            $notice = $_SESSION["flash"];
            unset($_SESSION["flash"]);
            $isError = $notice["type"] === "error";
            ?>
            <div class="notice <?= $isError ? "notice-error" : "notice-success" ?>" role="<?= $isError ? "alert" : "status" ?>">
                <?= e($notice["message"]) ?>
            </div>
        <?php endif; ?>
    <?php
}

function render_page_end(): void
{
    ?>
    </main>
    <footer class="site-footer">OnlyCars · Jaa oma autotarinasi.</footer>
    </body>
    </html>
    <?php
}
