<?php 

session_start();

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM user WHERE email = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($_POST["password"], $user["password_hash"])) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];

        header("Location: index.php");
        exit;
    }

    $is_invalid = true;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="/styles2.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bitcount+Prop+Single:wght@100..900&family=Caveat:wght@400..700&display=swap" rel="stylesheet">
</head>
</head>
<body>

    <h1>Login</h1>

    <?php if ($is_invalid): ?>
        <em>Invalid Login</em>
    <?php endif; ?>

    <form method="post">
        <label for="email">Email</label>
        <input 
            type="email" 
            name="email" id="email"
            value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
            >

        <label for="password">Password</label>
        <input 
            type="password" 
            name="password" 
            id="password">

        <button>Log in</button>
    </form>



</body>
</html>