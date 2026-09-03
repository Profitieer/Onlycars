<?php

require_once __DIR__ . "/auth.php";

// Get posts
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = mysqli_query($mysqli, $sql);

if (!$result) {
    die("Post query failed: " . mysqli_error($mysqli));
}

?>


<!DOCTYPE html>
<html lang="fi">
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

        <p>Hei <?= htmlspecialchars($user["name"]) ?></p>

        <p><a href="logout.php">Kirjaudu ulos</a></p>

<?php else: ?>

        <p><a href="login.php">Kirjaudu sisään</a> tai <a href="signup.html">Luo tili</a></p>
    
<?php endif; ?>



<?php include "header.php"; ?>

<?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>

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


<?php if ($user !== null && $user["id"] == ($row["user_id"] ?? null)): ?>
    
        <a id="post_changes1"
           href="edit_product.php?id=<?php echo $row["id"]; ?>">
            Muokkaa
        </a>

        <a id="post_changes2"
           href="delete_post.php?id=<?php echo $row["id"]; ?>">
            Poista
        </a>

<?php endif; ?>

    </div>
</div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Tuotteita ei löytynyt.</p>
<?php endif; ?>

</body>
</html>
