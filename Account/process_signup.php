<?php

mysqli_report(MYSQLI_REPORT_OFF);

// Check if name is empty
if (empty($_POST["name"])) {
    die("Name is Required");
}

// Check if email is empty
if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

// Check if password is atleast 8 characters
if (strlen($_POST["password"]) < 8) {
    die("Password must be atleast 8 characters");
}

// Check if password has atleast one letter
if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain atleast one letter");
}

// Check if password has atleast one number
if ( ! preg_match("/[0-9]/i", $_POST["password"])) {
    die("Password must contain atleast one number");
}

// Check if passwords match
if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("passwords must match");
}


$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database2.php";

$sql = "INSERT INTO user (name, email, password_hash)
        VALUES (?, ?, ?)";

$stmt = $mysqli->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss", 
                  $_POST["name"], 
                  $_POST["email"], 
                  $password_hash);
        
if ($stmt->execute()) {

    header("location: signup-success.html");
    exit;

} else {

    if ($stmt->errno === 1062) {
        die("email already taken");
    } else {
    die($mysqli->error . " " . $mysqli->errno);
    }

}

print_r($_POST);
var_dump($password_hash);