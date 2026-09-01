<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlycars";

$mysqli = new mysqli(
    $servername,
    $username,
    $password,
    $dbname
);

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

return $mysqli;
