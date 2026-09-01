<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Media</title>
    <link href="styles2.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitcount+Prop+Single:wght@100..900&family=Caveat:wght@400..700&display=swap" rel="stylesheet">
</head>
<body>


<?php

include "database.php";

$id = $_GET["id"];

// Get existing post
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

include "header.php";

?>

<div id="edit_post_background">

<form id="post_edit" method="post" enctype="multipart/form-data">

<h1> Julkaisun Muokkaus </h1>

    Nimi<input
        class="author"
        type="text"
        name="author"
        value="<?php echo htmlspecialchars($row["author"]); ?>"
    >

    <br><br>

    Merkki<input
        class="brand"
        type="text"
        name="brand"
        value="<?php echo htmlspecialchars($row["brand"]); ?>"
    >

    <br><br>

    Malli<input
        class="model"
        type="text"
        name="model"
        value="<?php echo htmlspecialchars($row["model"]); ?>"
    >

    <br><br>

    Julkaisu<textarea
        class="content"
        name="content"
    ><?php echo htmlspecialchars($row["content"]); ?></textarea>

    <br><br>

    <p>Nykyinen kuva:</p>

    <?php if (!empty($row["image"])): ?>

        <img
            src="<?php echo htmlspecialchars($row["image"]); ?>"
            alt="Nykyinen kuva"
            width="auto"
            height="200"
        >

    <?php else: ?>

        <p>Ei kuvaa.</p>

    <?php endif; ?>

    <p>Vaihda kuva:</p>

    <input
        type="file"
        name="image"
        accept="image/*"
    >

    <input
        type="hidden"
        name="id"
        value="<?php echo $row["id"]; ?>"
    >

    <br><br>

    <input
        class="submit"
        type="submit"
        value="Päivitä"
        id="päivitä"
    >

</form>

</div>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $id = $_POST["id"];
    $author = trim($_POST["author"]);
    $brand = trim($_POST["brand"]);
    $model = trim($_POST["model"]);
    $content = trim($_POST["content"]);

    // Check required fields
    if (empty($author) || empty($brand) || empty($model) || empty($content))
    {
        echo "Täytä kaikki kentät.";
    }
    else
    {
        /*
         * Keep the existing image by default.
         */
        $imagePath = $row["image"];

        /*
         * Check if a new image was uploaded.
         */
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK)
        {
            $uploadDir = "kuvat/";

            if (!is_dir($uploadDir))
            {
                mkdir($uploadDir, 0755, true);
            }

            // Get file extension
            $extension = strtolower(
                pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
            );

            // Allowed extensions
            $allowed = ["jpg", "jpeg", "png", "gif", "webp"];

            if (!in_array($extension, $allowed))
            {
                die("Invalid image type.");
            }

            // Create new filename
            $filename = uniqid("", true) . "." . $extension;

            $newImagePath = $uploadDir . $filename;

            // Move uploaded image
            if (move_uploaded_file(
                $_FILES["image"]["tmp_name"],
                $newImagePath
            ))
            {
                $imagePath = $newImagePath;
            }
            else
            {
                die("Failed to upload image.");
            }
        }

        // Update post
        $sql = "UPDATE posts
                SET author = ?,
                    brand = ?,
                    model = ?,
                    content = ?,
                    image = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($mysqli, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "sssssi",
            $author,
            $brand,
            $model,
            $content,
            $imagePath,
            $id
        );

        if (mysqli_stmt_execute($stmt))
        {
            echo "Julkaisu päivitettiin onnistuneesti.";

            echo "<script>
                    window.location.href = 'index.php';
                  </script>";
        }
        else
        {
            echo "Julkaisun päivittäminen epäonnistui.";
        }
    }
}

?>

</body>
</html>