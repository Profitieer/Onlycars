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

$id = $_GET["id"];

include "database.php";
include "header.php";

?>

<div id="post_delete_background">

<form id="post_delete" method="POST">

    <input
        id="delete_input"
        type="hidden"
        name="id"
        value="<?php echo htmlspecialchars($id); ?>"
    >

    <p id="delete_p">
        Haluatko varmasti poistaa julkaisun?
    </p>

    <button id="delete_submit" type="submit">
        Poista
    </button>

</form>

</div>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $id = $_POST["id"];

    /*
     * First get the image path from the database.
     */
    $sql = "SELECT image FROM posts WHERE id = ?";

    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    $imagePath = $row["image"] ?? "";


    /*
     * Delete the post from the database.
     */
    $sql = "DELETE FROM posts WHERE id = ?";

    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt))
    {
        /*
         * Delete the image file from the server.
         */
        if (!empty($imagePath) && file_exists($imagePath))
        {
            unlink($imagePath);
        }

        echo "Julkaisu poistettiin onnistuneesti.";

        echo "<script>
                window.location.href = 'index.php';
              </script>";
    }
    else
    {
        echo "Poistaminen epäonnistui.";
    }
}

?>

</body>
</html>