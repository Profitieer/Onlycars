<?php

require_once __DIR__ . "/auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect_to("signup.php");
}

verify_csrf_token();
$next = safe_return_path($_POST["next"] ?? "index.php");
$signupUrl = "signup.php?next=" . rawurlencode($next);
$signupName = post_text("name");
$signupEmail = post_text("email");
$signupPassword = is_string($_POST["password"] ?? null) ? $_POST["password"] : "";
$confirmation = is_string($_POST["password_confirmation"] ?? null) ? $_POST["password_confirmation"] : "";

// Keep the name and email when validation fails. Never store passwords here.
$_SESSION["forms"]["signup"] = ["name" => $signupName, "email" => $signupEmail];
$errorMessage = "";

if ($signupName === "") {
    $errorMessage = "Nimi on pakollinen.";
} elseif (!filter_var($signupEmail, FILTER_VALIDATE_EMAIL)) {
    $errorMessage = "Anna kelvollinen sähköpostiosoite.";
} elseif (strlen($signupPassword) < 8) {
    $errorMessage = "Salasanassa pitää olla vähintään 8 merkkiä.";
} elseif (!preg_match("/[a-z]/i", $signupPassword) || !preg_match("/[0-9]/", $signupPassword)) {
    $errorMessage = "Salasanassa pitää olla vähintään yksi kirjain ja yksi numero.";
} elseif ($signupPassword !== $confirmation) {
    $errorMessage = "Salasanat eivät täsmää.";
}

if ($errorMessage !== "") {
    flash($errorMessage, "error");
    redirect_to($signupUrl);
}

$signupPasswordHash = password_hash($signupPassword, PASSWORD_DEFAULT);

try {
    $mysqli = require __DIR__ . "/database.php";
    $stmt = $mysqli->prepare("INSERT INTO user (name, email, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $signupName, $signupEmail, $signupPasswordHash);
    $stmt->execute();
} catch (mysqli_sql_exception $error) {
    $message = (int) $error->getCode() === 1062
        ? "Sähköpostiosoite on jo käytössä. Kirjaudu sisään tai käytä toista osoitetta."
        : "Tilin luominen epäonnistui. Yritä myöhemmin uudelleen.";
    flash($message, "error");
    redirect_to($signupUrl);
}

unset($_SESSION["forms"]["signup"]);
$_SESSION["forms"]["login"] = ["email" => $signupEmail];
flash("Tili luotiin onnistuneesti. Voit nyt kirjautua sisään.");
redirect_to("login.php?next=" . rawurlencode($next));
