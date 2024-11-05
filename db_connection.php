<?php
$servername = "localhost";   // MySQL server (localhost for XAMPP)
$username = "root";          // MySQL username (default is "root" in XAMPP)
$password = "0000";              // MySQL password (leave blank if no password)
$dbname = "pharmaceuticals";    // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
