<?php
$servername = "localhost";
$username = "root";  // XAMPP default username
$password = "";      // XAMPP default password
$dbname = "hospital_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to handle Turkish characters correctly
$conn->set_charset("utf8mb4");
?>