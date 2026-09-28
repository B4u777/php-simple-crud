<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "crud_app";

$conn = new mysqli($host, $username, $password, $database);

// Connection error
if ($conn->connect_error) {
    die("Database connection failed.");
}

// UTF-8 support
$conn->set_charset("utf8mb4");



?>