<?php

require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/page.php";
require_once __DIR__ . "/post_form.php";
require_once __DIR__ . "/post_images.php";

$mysqli = require __DIR__ . "/database.php";
$user = require_login($mysqli);
$userId = (int) $user["id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verify_csrf_token();
}

$row = get_owned_post($mysqli, $userId);
$id = (int) $row["id"];
$formKey = "edit_" . $id;
$imagePath = $row["image"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $values = [
        "brand" => post_text("brand"),
        "model" => post_text("model"),
        "content" => post_text("content")
    ];

    try {
        $brand = required_post_text("brand");
        $model = required_post_text("model");
        $content = required_post_text("content");
        $imagePath = save_post_image($imagePath);

        $stmt = $mysqli->prepare(
            "UPDATE posts SET brand = ?, model = ?, content = ?, image = ? WHERE id = ? AND user_id = ?"
        );
        $stmt->bind_param("ssssii", $brand, $model, $content, $imagePath, $id, $userId);
        $stmt->execute();
    } catch (mysqli_sql_exception $error) {
        if ($imagePath !== ($row["image"] ?? "")) {
            discard_uploaded_image($imagePath);
        }
        $_SESSION["forms"][$formKey] = $values;
        flash("Tallentaminen epäonnistui. Yritä uudelleen ja valitse kuva uudestaan.", "error");
        redirect_to("edit_product.php?id=" . $id);
    } catch (RuntimeException | InvalidArgumentException $error) {
        $_SESSION["forms"][$formKey] = $values;
        flash($error->getMessage() . " Valitse mahdollinen kuva uudelleen.", "error");
        redirect_to("edit_product.php?id=" . $id);
    }

    if ($imagePath !== ($row["image"] ?? "")) {
        if ($stmt->affected_rows > 0) {
            remove_unused_post_image($mysqli, $row["image"]);
        } else {
            discard_uploaded_image($imagePath);
        }
    }

    unset($_SESSION["forms"][$formKey]);
    flash("Julkaisun muutokset tallennettiin.");
    redirect_to("index.php");
}

$values = array_merge($row, take_form_values($formKey));
render_page_start("Muokkaa julkaisua", $user);
?>

<section class="card form-card">
    <h1>Muokkaa julkaisua</h1>
    <p class="intro">Päivitä autosi tiedot, tarina tai kuva.</p>
    <?php render_post_form($values, $row["author"], $id, $imagePath); ?>
</section>

<?php render_page_end(); ?>
