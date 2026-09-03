<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/page.php";

$mysqli = require __DIR__ . "/database.php";
$user = get_current_user($mysqli);
$next = safe_return_path($_POST["next"] ?? $_GET["next"] ?? "index.php");
$loginUrl = "login.php?next=" . rawurlencode($next);

<<<<<<< HEAD
if ($user !== null) {
    redirect_to($next);
=======
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM user WHERE email = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($_POST["password"], $user["password_hash"])) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        unset($_SESSION["csrf_token"]);

        header("Location: index.php");
        exit;
    }

    $is_invalid = true;
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf_token();
    $loginEmail = post_text("email");
    $loginPassword = is_string($_POST["password"] ?? null) ? $_POST["password"] : "";
    $_SESSION["forms"]["login"] = ["email" => $loginEmail];

    try {
        $stmt = $mysqli->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $loginEmail);
        $stmt->execute();
        $account = $stmt->get_result()->fetch_assoc();
    } catch (mysqli_sql_exception $error) {
        flash("Kirjautuminen epäonnistui. Yritä hetken kuluttua uudelleen.", "error");
        redirect_to($loginUrl);
    }

    if ($account && password_verify($loginPassword, $account["password_hash"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $account["id"];
        unset($_SESSION["csrf_token"], $_SESSION["forms"]["login"]);
        flash("Kirjautuminen onnistui.");
        redirect_to($next);
    }

    flash("Sähköpostiosoite tai salasana on väärin.", "error");
    redirect_to($loginUrl);
}

$values = take_form_values("login");
render_page_start("Kirjaudu sisään", $user, "login");
?>

<<<<<<< HEAD
<section class="card form-card compact-card">
    <h1>Kirjaudu sisään</h1>
    <p class="intro">Kirjaudu julkaistaksesi ja muokataksesi omia julkaisujasi.</p>
    <form action="login.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="next" value="<?= e($next) ?>">
        <div class="field">
            <label for="email">Sähköposti</label>
            <input type="email" id="email" name="email" value="<?= e($values["email"] ?? "") ?>" autocomplete="email" required>
        </div>
        <div class="field">
            <label for="password">Salasana</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>
        </div>
        <div class="actions">
            <button class="button" type="submit">Kirjaudu sisään</button>
            <a class="button button-secondary" href="index.php">Etusivulle</a>
        </div>
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="styles2.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitcount+Prop+Single:wght@100..900&family=Caveat:wght@400..700&display=swap" rel="stylesheet">
</head>
<body>

    <h1>Login</h1>

    <?php if ($is_invalid): ?>
        <em>Invalid Login</em>
    <?php endif; ?>

    <form method="post">
        <label for="email">Email</label>
        <input 
            type="email" 
            name="email" id="email"
            value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
            >

        <label for="password">Password</label>
        <input 
            type="password" 
            name="password" 
            id="password">

        <button>Log in</button>
>>>>>>> e935e74681cf530bbeb5e3105a56d0691e2bba7f
    </form>
    <p class="form-footer">Ei vielä tiliä? <a href="signup.php?next=<?= e(rawurlencode($next)) ?>">Luo tili</a></p>
</section>

<?php render_page_end(); ?>
