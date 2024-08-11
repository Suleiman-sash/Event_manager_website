<?php
$servername = "localhost";
$username = "root";
$password = ""; // Change this to your MySQL password
$dbname = "event";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
