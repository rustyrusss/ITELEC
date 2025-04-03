<?php

$host = "localhost"; 
$user = "root"; 
$password = ""; 
$database = "login"; 

// Create the database connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Failed to connect to Database: " . $conn->connect_error);
}
?>