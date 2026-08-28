<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlycars";

$conn = mysqli_connect( $servername, $username, $password, $dbname );



if (!$conn) {
    die("Yhteys epäonnistui." . mysqli_connect_error());
}
?>

