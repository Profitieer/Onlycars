<?php

// Start the session before sending any HTML to the browser.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/database.php";

$user = null;

// Check that the account in the session still exists.
if (isset($_SESSION["user_id"])) {
    $stmt = $mysqli->prepare("SELECT id, name FROM user WHERE id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();

    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        $user = null;
        unset($_SESSION["user_id"]);
    }
}

function require_login()
{
    global $user;

    if (!$user) {
        header("Location: login.php");
        exit;
    }
}

// Only forms opened in this session may change posts.
function csrf_token()
{
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    return $_SESSION["csrf_token"];
}

function verify_csrf_token()
{
    $token = $_POST["csrf_token"] ?? "";
    $expected = $_SESSION["csrf_token"] ?? "";

    if (!is_string($token) || $expected === "" || !hash_equals($expected, $token)) {
        http_response_code(403);
        exit("Lomake on vanhentunut. Avaa sivu uudelleen ja yritä uudestaan.");
    }
}

function get_owned_post($mysqli, $userId)
{
    // On submit, check the same ID that will be used in UPDATE or DELETE.
    $input = $_SERVER["REQUEST_METHOD"] === "POST" ? $_POST : $_GET;
    $id = filter_var($input["id"] ?? null, FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1]
    ]);

    if ($id === false) {
        http_response_code(400);
        exit("Virheellinen julkaisun tunnus.");
    }

    $stmt = $mysqli->prepare(
        "SELECT * FROM posts WHERE id = ? AND user_id = ?"
    );
    $stmt->bind_param("ii", $id, $userId);
    $stmt->execute();

    $post = $stmt->get_result()->fetch_assoc();

    if (!$post) {
        http_response_code(404);
        exit("Julkaisua ei löytynyt tai sinulla ei ole oikeutta muokata sitä.");
    }

    return $post;
}

function required_post_text($field)
{
    $value = $_POST[$field] ?? "";

    if (!is_string($value) || trim($value) === "") {
        http_response_code(400);
        exit("Täytä kaikki pakolliset kentät.");
    }

    return trim($value);
}
