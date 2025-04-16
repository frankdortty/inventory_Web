<?php
session_start();  // Start the session
// Database connection
include('../const/db.php');

// Ensure the company name is set in the session
if (isset($_SESSION['companyName'])) {
    $companyName = $_SESSION['companyName'];

    // Fetch products from the database filtered by company name
    $query = "SELECT * FROM products WHERE company = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $companyName);  // Bind the company name as a parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if products are available
    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    $stmt->close();
} else {
    // Handle the case where the session company name is not set
    echo "Company name not found in session.";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<?php include("../const/head.php") ?>
<body>
    <div class="viewProduct">
        <div class="container">
            <header>
                <button onclick="goBack()" class="back-button">←</button>
                <h2>Product</h2>
            </header>

            <div class="search-container">
                <input type="text" id="searchBox" placeholder="Search..." class="search-box">
            </div>

            <div class="product-list">
                <h3>Best Sale Product</h3>
                <div class="productsList" id="productsList">
                    <div class="productBoxes">
                        <?php foreach ($products as $product): ?>
                            <div class="productBox" data-id="<?php echo $product['id']; ?>" data-name="<?php echo $product['name']; ?>" style="background-image: url('./uploads/products/<?php echo $product['image']; ?>');">
                                <div class="text">
                                    <div class="name"><?php echo $product['name']; ?></div>
                                    <div class="price">$<?php echo number_format($product['sale_price'], 2); ?></div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <button class="add-button" onclick="window.location.href ='./addProduct.php'">+</button>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

        // Real-time search functionality
        document.getElementById("searchBox").addEventListener("input", function() {
            const searchQuery = document.getElementById("searchBox").value.toLowerCase();
            const productBoxes = document.querySelectorAll(".productBox");

            productBoxes.forEach(function(box) {
                const productName = box.getAttribute("data-name").toLowerCase();
                if (productName.includes(searchQuery)) {
                    box.style.display = "block";
                } else {
                    box.style.display = "none";
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
    // Select all product boxes
    const productBoxes = document.querySelectorAll(".productBox");

    // Add click event listener for each product box
    productBoxes.forEach(function(box) {
        box.addEventListener("click", function() {
            // Get the product ID from the data-id attribute
            const productId = box.getAttribute("data-id");

            // Redirect to productDetails.php with the product ID in a query string
            window.location.href = "productDetails.php?id=" + productId;
        });
    });
});

    </script>
    
</body>
</html>
