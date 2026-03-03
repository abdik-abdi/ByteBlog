<?php
$servername = "localhost";
$username = "root"; // default for XAMPP
$password = "";     // default for XAMPP
$database = "byteblog"; // your actual database name

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
