<?php
header('Content-Type: application/json');

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barcode_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $response = ['error' => 'Database connection failed: ' . $conn->connect_error];
    echo json_encode($response);
    exit();
}

// Retrieve barcode from query parameter
$barcode = isset($_GET['barcode']) ? trim($_GET['barcode']) : '';

// Validate barcode
if (empty($barcode)) {
    $response = ['error' => 'Barcode is required'];
    echo json_encode($response);
    exit();
}

// Debug: Output the received barcode
error_log('Received barcode: ' . $barcode);

// Prepare the SQL query
$sql = "SELECT * FROM products WHERE barcode = ?";
$stmt = $conn->prepare($sql);

// Check if statement preparation was successful
if ($stmt === false) {
    $response = ['error' => 'Failed to prepare statement: ' . $conn->error];
    echo json_encode($response);
    exit();
}

// Bind parameters and execute the query
$stmt->bind_param("s", $barcode);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query executed successfully
if ($result === false) {
    $response = ['error' => 'Failed to execute query: ' . $stmt->error];
    echo json_encode($response);
    exit();
}

// Check if product was found
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response = [ 
        'product_name' => $row['product_name'],
        'image_url' => $row['image_url'],
        'description' => $row['description'],
        'available_sizes' => $row['available_sizes'],
        'price' => $row['price'],
    ];
} else {
    // Debug: Output the executed SQL query
    $response = ['error' => 'No product found for the provided barcode.'];
    error_log('Query: ' . $sql . ' Barcode: ' . $barcode);
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Output the response as JSON
echo json_encode($response);
?>