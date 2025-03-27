<?php

$host = "localhost"; 
$user = "root"; 
$password = ""; 
$database = "login"; 

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    echo "Failed to connect Database".$conn->connect_error;
}
?>