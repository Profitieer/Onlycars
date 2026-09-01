<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlyCars</title>
    <link href="styles2.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitcount+Prop+Single:wght@100..900&family=Caveat:wght@400..700&display=swap" rel="stylesheet">
</head>
<body>

<?php if (isset($user)): ?>

        <p>Hello <?= htmlspecialchars($user["name"]) ?></p>

        <p><a href="logout.php">Log out</a></p>

<?php else: ?>

        <p><a href="Account/login.php">Log in</a> or <a href="Account/signup.html">Sign up</a></p>
    
<?php endif; ?>

<?php

include "database.php";

include "header.php";

$sql = "SELECT * FROM posts ORDER BY created_at DESC"; 
$result = mysqli_query($conn, $sql);

session_start();

if (isset($_SESSION["user_id"])) {

    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

}
?>

<?php
// Check if there are any results
if (mysqli_num_rows($result) > 0)
{
    // Output data of each row
    while($row = mysqli_fetch_assoc($result))
    {

?>

<!-- Display the data in a structured format -->
<div class="backgroundposts">
    <div class="post">

        <h2><?php echo htmlspecialchars($row["author"]); ?></h2>

        <p>
            <?php echo htmlspecialchars($row["brand"] . " "); ?>
            <?php echo htmlspecialchars($row["model"]); ?>
        </p>

        <?php if (!empty($row["image"])): ?>
            <img src="<?php echo htmlspecialchars($row["image"]); ?>" alt="Post image">
        <?php endif; ?>

        <p><?php echo htmlspecialchars($row["content"]); ?></p>

        <small>
            <?php echo htmlspecialchars($row["created_at"]); ?>
        </small>

        <br><br>

        <a id="post_changes1"
           href="edit_product.php?id=<?php echo $row["id"]; ?>">
            Muokkaa
        </a>

        <a id="post_changes2"
           href="delete_post.php?id=<?php echo $row["id"]; ?>">
            Poista
        </a>

    </div>
</div>
<?php

    } // End of while loop
}   else{
    // If no results were found, display a message
    echo "Tuotteita ei löytynyt.";
        }


?>

</body>
</html>

