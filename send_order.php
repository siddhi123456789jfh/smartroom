<?php
// Admin email
$admin_email = "siddhi.bhekare@somaiya.edu";

// Get the product details from the POST request
$product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
$size = isset($_POST['size']) ? $_POST['size'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';

// Validate the data
if (empty($product_name) || empty($size) || empty($price)) {
    echo 'Invalid order details';
    exit();
}

// Prepare the email content
$subject = "New Order: Bring it to me!";
$message = "You have a new order:\n";
$message .= "Product Name: $product_name\n";
$message .= "Selected Size: $size\n";
$message .= "Price: $$price\n";

// Send the email to the admin
$headers = "From: no-reply@example.com\r\n";
if (mail($admin_email, $subject, $message, $headers)) {
    echo 'Order placed successfully!';
} else {
    echo 'Failed to send the order!';
}
?>
