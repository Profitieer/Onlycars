<?php

// Both pages supply their values explicitly, so the form has no global variables.
function render_post_form(array $values, string $author, ?int $id = null, string $imagePath = ""): void
{
    $isEdit = $id !== null;
    $action = $isEdit ? "edit_product.php?id=" . $id : "upload.php";
    ?>
    <form action="<?= e($action) ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= $id ?>">
        <?php endif; ?>

        <div class="field">
            <label for="author">Kirjoittaja</label>
            <input id="author" type="text" value="<?= e($author) ?>" readonly>
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="brand">Merkki</label>
                <input id="brand" name="brand" value="<?= e($values["brand"] ?? "") ?>" required>
            </div>
            <div class="field">
                <label for="model">Malli</label>
                <input id="model" name="model" value="<?= e($values["model"] ?? "") ?>" required>
            </div>
        </div>

        <div class="field">
            <label for="content">Julkaisu</label>
            <textarea id="content" name="content" placeholder="Kerro autostasi..." required><?= e($values["content"] ?? "") ?></textarea>
        </div>

        <?php if ($isEdit && $imagePath !== ""): ?>
            <div class="field">
                <p>Nykyinen kuva</p>
                <img class="current-image" src="<?= e($imagePath) ?>" alt="Julkaisun nykyinen kuva">
            </div>
        <?php endif; ?>

        <div class="field">
            <label for="image"><?= $isEdit ? "Vaihda kuva (valinnainen)" : "Kuva (valinnainen)" ?></label>
            <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" aria-describedby="image-help">
            <small id="image-help" class="field-help">JPG, PNG, GIF tai WEBP. <?= $isEdit ? "Nykyinen kuva säilyy, jos et valitse uutta." : "Voit julkaista myös ilman kuvaa." ?></small>
        </div>

        <div class="actions">
            <button class="button" type="submit"><?= $isEdit ? "Tallenna muutokset" : "Julkaise" ?></button>
            <a class="button button-secondary" href="index.php">Peruuta</a>
        </div>
    </form>
    <?php
}
