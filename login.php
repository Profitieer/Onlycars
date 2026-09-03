<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/page.php";

$mysqli = require __DIR__ . "/database.php";
$user = get_current_user($mysqli);
$next = safe_return_path($_POST["next"] ?? $_GET["next"] ?? "index.php");
$loginUrl = "login.php?next=" . rawurlencode($next);

if ($user !== null) {
    redirect_to($next);
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
    </form>
    <p class="form-footer">Ei vielä tiliä? <a href="signup.php?next=<?= e(rawurlencode($next)) ?>">Luo tili</a></p>
</section>

<?php render_page_end(); ?>
