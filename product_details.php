<?php
header('Content-Type: text/html');

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

// Retrieve barcode from query parameter
$barcode = isset($_GET['barcode']) ? trim($_GET['barcode']) : '';

// Validate barcode
if (empty($barcode)) {
    echo '<p>Barcode is required</p>';
    exit();
}

// Prepare the SQL query
$sql = "SELECT * FROM products WHERE barcode = ?";
$stmt = $conn->prepare($sql);

// Check if statement preparation was successful
if ($stmt === false) {
    die('Failed to prepare statement: ' . $conn->error);
}

// Bind parameters and execute the query
$stmt->bind_param("s", $barcode);
$stmt->execute();
$result = $stmt->get_result();

// Check if product was found
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Split available sizes into an array
    $availableSizes = explode(',', $row['available_sizes']);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Details</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f8f8;
                padding: 20px;
            }
            .product-container {
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            img {
                max-width: 100%;
                height: auto;
            }
            h1 {
                text-align: center;
                color: #333;
            }
            p {
                font-size: 16px;
                color: #666;
            }
            .size-container {
                margin-top: 20px;
            }
            .size-option {
                margin-right: 10px;
            }
            .btn {
                display: inline-block;
                background-color: #28a745;
                color: white;
                padding: 10px 15px;
                font-size: 16px;
                text-align: center;
                text-decoration: none;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            .btn:hover {
                background-color: #218838;
            }
        </style>
    </head>
    <body>
        <div class="product-container">
            <h1><?php echo $row['product_name']; ?></h1>
            <img src="<?php echo $row['image_url']; ?>" alt="Product Image">
            <p>Description: <?php echo $row['description']; ?></p>
            <div class="size-container">
                <p>Select Size:</p>
                <?php foreach ($availableSizes as $size): ?>
                    <label class="size-option">
                        <input type="radio" name="size" value="<?php echo $size; ?>"> <?php echo $size; ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <p>Price: $<?php echo $row['price']; ?></p>

            <!-- Bring it to me button -->
            <button class="btn" onclick="orderProduct('<?php echo addslashes($row['product_name']); ?>', <?php echo $row['price']; ?>)">Bring it to me</button>
        </div>

        <script>
            function orderProduct(productName, price) {
                const selectedSize = document.querySelector('input[name="size"]:checked');
                if (selectedSize) {
                    const size = selectedSize.value;

                    // Create an XMLHttpRequest object
                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "place_order.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            alert(xhr.responseText); // Show the response from the server
                        }
                    };
                    // Send the request with the product name, size, and price
                    xhr.send("product_name=" + encodeURIComponent(productName) + "&size=" + encodeURIComponent(size) + "&price=" + encodeURIComponent(price));
                } else {
                    alert("Please select a size before proceeding.");
                }
            }
        </script>
    </body>
    </html>
    <?php
} else {
    echo '<p>No product found for the provided barcode.</p>';
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
