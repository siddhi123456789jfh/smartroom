<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barcode_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully to the database.";
}
// Close the connection
$conn->close();
?>
