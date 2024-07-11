<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "Soufiane@2003";
$dbname = "intranet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
