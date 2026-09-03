<?php

require_once __DIR__ . "/auth.php";
require_login();

?>

<!DOCTYPE html>
<html lang="fi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>OnlyCars</title>

    <link href="styles2.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Bitcount+Prop+Single:wght@100..900&family=Caveat:wght@400..700&display=swap" rel="stylesheet">
</head>

<body>

<?php

include "header.php";

?>

<div class="backgroundpostscreate">

    <div class="postcreate">

        <h2>Luo julkaisu</h2>

        <form action="upload.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">

            <label for="author">Nimi</label>
            <input
                type="text"
                id="author"
                value="<?= htmlspecialchars($user["name"]) ?>"
                readonly
            >

            <br><br>

            <label for="brand">Merkki</label>
            <input
                type="text"
                id="brand"
                name="brand"
                placeholder="..."
                required
            >

            <br><br>

            <label for="model">Malli</label>
            <input
                type="text"
                id="model"
                name="model"
                placeholder="..."
                required
            >

            <br><br>

            <label for="content">Julkaisu</label>
            <textarea
                id="content"
                name="content"
                placeholder="Kirjoita julkaisu..."
                required
            ></textarea>

            <br><br>

            <label for="image">Kuva</label>
            <input
                type="file"
                id="image"
                name="image"
                accept="image/jpeg, image/png, image/gif, image/webp"
            >

            <br><br>

            <button id="submit" type="submit">
                Julkaise
            </button>

        </form>

    </div>

</div>

</body>
</html>