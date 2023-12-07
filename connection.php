<?php

$databaseServerName = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseName = "motorbikes";

$conn = mysqli_connect($databaseServerName, $databaseUsername, $databasePassword, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
