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
    die('Database connection failed: ' . $conn->connect_error);
}

// Get the product details from the POST request
$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
$size = isset($_POST['size']) ? $_POST['size'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';

// Validate the data
if (empty($product_name) || empty($size) || empty($price)) {
    echo 'Invalid order details';
    exit();
}

// Insert order into the 'orders' table
$sql = "INSERT INTO orders (product_name, size, price) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssd", $product_name, $size, $price);
if ($stmt->execute()) {
    echo 'Order placed successfully!';
} else {
    echo 'Failed to place order: ' . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
