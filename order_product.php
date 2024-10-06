<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <style>
        /* Styling omitted for brevity */
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
        <button class="btn" onclick="orderProduct()">Bring it to me</button>
    </div>

    <script>
        function orderProduct() {
            const selectedSize = document.querySelector('input[name="size"]:checked');
            const productName = '<?php echo $row['product_name']; ?>';
            const productPrice = '<?php echo $row['price']; ?>';

            if (selectedSize) {
                // Use AJAX to send order details to the server
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'send_order.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        alert('Your order has been placed!');
                    } else {
                        alert('There was an issue placing your order.');
                    }
                };

                xhr.send('product_name=' + encodeURIComponent(productName) + 
                         '&size=' + encodeURIComponent(selectedSize.value) + 
                         '&price=' + encodeURIComponent(productPrice));
            } else {
                alert("Please select a size before proceeding.");
            }
        }
    </script>
</body>
</html>
