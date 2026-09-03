<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value): string
{
    return htmlspecialchars((string) ($value ?? ""), ENT_QUOTES, "UTF-8");
}

// Always redirect before rendering HTML. POST becomes a GET after redirecting.
/** @return never */
function redirect_to(string $path)
{
    $status = ($_SERVER["REQUEST_METHOD"] ?? "GET") === "POST" ? 303 : 302;
    header("Location: " . $path, true, $status);
    exit;
}

// Login may only return to one of our own pages.
function safe_return_path($path): string
{
    if (is_string($path) && preg_match(
        '~^(index\.php|create_post\.php|(edit_product|delete_post)\.php\?id=[1-9][0-9]*)$~D',
        $path
    )) {
        return $path;
    }

    return "index.php";
}

function flash(string $message, string $type = "success"): void
{
    $_SESSION["flash"] = ["message" => $message, "type" => $type];
}

function post_text(string $key): string
{
    return is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : "";
}

function take_form_values(string $key): array
{
    $values = $_SESSION["forms"][$key] ?? [];
    unset($_SESSION["forms"][$key]);
    return is_array($values) ? $values : [];
}

/** @return never */
function show_error(string $message, int $status = 400)
{
    http_response_code($status);
    require_once __DIR__ . "/page.php";
    render_page_start("Pyyntöä ei voitu suorittaa", null);
    ?>
    <section class="card form-card">
        <h1>Pyyntöä ei voitu suorittaa</h1>
        <p><?= e($message) ?></p>
        <a class="button" href="index.php">Takaisin etusivulle</a>
    </section>
    <?php
    render_page_end();
    exit;
}
