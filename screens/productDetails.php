<?php
session_start();
include('../const/db.php');

// Check if the product ID is passed
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product details from the database
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);  // Bind the product ID as an integer
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "Product not found.";
        exit;
    }

    $stmt->close();
} else {
    echo "Product ID is missing.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<?php include("../const/head.php") ?>
<head>
    <style>
        /* Basic styling for the product details page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .product-details {
            padding: 20px;
            background-color: white;
            margin: 30px auto;
            max-width: 900px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .container {
            padding: 20px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .back-button {
            background-color: #3498db;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #2980b9;
        }

        .product-info {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .product-image {
            width: 40%;
            height: 300px;
            background-size: cover;
            background-position: center;
            margin-right: 20px;
        }

        .product-details-text {
            width: 55%;
        }

        .product-details-text h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .product-details-text p {
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
        }

        .edit-button, .delete-button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .edit-button {
            background-color: #2ecc71;
            color: white;
        }

        .edit-button:hover {
            background-color: #27ae60;
        }

        .delete-button {
            background-color: #e74c3c;
            color: white;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="product-details">
        <div class="container">
            <header>
                <button onclick="goBack()" class="back-button">←</button>
                <h2>Product Details</h2>
            </header>

            <div class="product-info">
                <div class="product-image" style="background-image: url('./uploads/products/<?php echo $product['image']; ?>');"></div>
                <div class="product-details-text">
                    <h3><?php echo $product['name']; ?></h3>
                    <p><strong>Description:</strong> <?php echo $product['description']; ?></p>
                    <p><strong>Cost Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                    <p><strong>Sales Price:</strong> $<?php echo number_format($product['sale_price'], 2); ?></p>
                    <p><strong>Category:</strong> <?php echo $product['category']; ?></p>
                    <p><strong>Quantity:</strong> <?php echo $product['quantity']; ?></p>
                    <p><strong>Created At:</strong> <?php echo date('F j, Y, g:i a', strtotime($product['created_at'])); ?></p>
                    <p><strong>Updated At:</strong> <?php echo date('F j, Y, g:i a', strtotime($product['updated_at'])); ?></p>
                </div>
            </div>

            <div class="action-buttons">
                <button class="edit-button" onclick="window.location.href = 'editProduct.php?id=<?php echo $product['id']; ?>'">Edit</button>
                <button class="delete-button" onclick="deleteProduct(<?php echo $product['id']; ?>)">Delete</button>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        function deleteProduct(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                // Perform AJAX or a form submission to delete the product
                window.location.href = 'deleteProduct.php?id=' + productId;
            }
        }
    </script>
</body>
</html>
