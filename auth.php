<?php

require_once __DIR__ . "/helpers.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Receive the connection explicitly instead of using an included variable.
function get_current_user(mysqli $mysqli): ?array
{
    $userId = filter_var($_SESSION["user_id"] ?? null, FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1]
    ]);

    if ($userId === false) {
        unset($_SESSION["user_id"]);
        return null;
    }

    $stmt = $mysqli->prepare("SELECT id, name FROM user WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        unset($_SESSION["user_id"]);
        return null;
    }

    return $user;
}

// Return the account so the calling page can assign its own $user variable.
function require_login(mysqli $mysqli): array
{
    $user = get_current_user($mysqli);

    if ($user === null) {
        $page = basename($_SERVER["SCRIPT_NAME"] ?? "index.php");
        $id = $_POST["id"] ?? $_GET["id"] ?? null;
        $returnPath = $page;

        if (in_array($page, ["edit_product.php", "delete_post.php"], true)) {
            $validId = filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
            $returnPath .= "?id=" . ($validId === false ? "" : $validId);
        }

        // A direct upload request returns to the create form after login.
        if ($page === "upload.php") {
            $returnPath = "create_post.php";
        }

        redirect_to("login.php?next=" . rawurlencode(safe_return_path($returnPath)));
    }

    return $user;
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
        show_error("Lomake on vanhentunut. Avaa sivu uudelleen ja yritä uudestaan.", 403);
    }
}

function get_owned_post(mysqli $mysqli, int $userId): array
{
    // On submit, check the same ID that will be used in UPDATE or DELETE.
    $input = $_SERVER["REQUEST_METHOD"] === "POST" ? $_POST : $_GET;
    $id = filter_var($input["id"] ?? null, FILTER_VALIDATE_INT, [
        "options" => ["min_range" => 1]
    ]);

    if ($id === false) {
        show_error("Virheellinen julkaisun tunnus.", 400);
    }

    $stmt = $mysqli->prepare(
        "SELECT * FROM posts WHERE id = ? AND user_id = ?"
    );
    $stmt->bind_param("ii", $id, $userId);
    $stmt->execute();

    $post = $stmt->get_result()->fetch_assoc();

    if (!$post) {
        show_error("Julkaisua ei löytynyt tai sinulla ei ole oikeutta muokata sitä.", 404);
    }

    return $post;
}

function required_post_text($field)
{
    $value = $_POST[$field] ?? "";

    if (!is_string($value) || trim($value) === "") {
        throw new InvalidArgumentException("Täytä kaikki pakolliset kentät.");
    }

    return trim($value);
}
