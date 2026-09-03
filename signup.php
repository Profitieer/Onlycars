<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/page.php";

$mysqli = require __DIR__ . "/database.php";
$user = get_current_user($mysqli);
$next = safe_return_path($_GET["next"] ?? "index.php");

if ($user !== null) {
    redirect_to($next);
}

$values = take_form_values("signup");
render_page_start("Luo tili", $user, "signup");
?>

<section class="card form-card compact-card">
    <h1>Luo tili</h1>
    <p class="intro">Liity mukaan ja jaa oma autotarinasi.</p>
    <form action="process_signup.php" method="post" id="signup">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="next" value="<?= e($next) ?>">
        <div class="field">
            <label for="name">Nimi</label>
            <input id="name" name="name" value="<?= e($values["name"] ?? "") ?>" autocomplete="name" required>
        </div>
        <div class="field">
            <label for="email">Sähköposti</label>
            <input type="email" id="email" name="email" value="<?= e($values["email"] ?? "") ?>" autocomplete="email" required>
        </div>
        <div class="field">
            <label for="password">Salasana</label>
            <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" aria-describedby="password-help" required>
            <small id="password-help" class="field-help">Vähintään 8 merkkiä, yksi kirjain ja yksi numero.</small>
        </div>
        <div class="field">
            <label for="password_confirmation">Salasana uudelleen</label>
            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" minlength="8" required>
        </div>
        <div class="actions">
            <button class="button" type="submit">Luo tili</button>
            <a class="button button-secondary" href="index.php">Peruuta</a>
        </div>
    </form>
    <p class="form-footer">Onko sinulla jo tili? <a href="login.php?next=<?= e(rawurlencode($next)) ?>">Kirjaudu sisään</a></p>
</section>

<script src="validation.js" defer></script>
<?php render_page_end(); ?>
