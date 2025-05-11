<?php
session_start();  // Start the session
// Database connection
include('const/db.php');

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
<?php include("const/head.php") ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keep Track</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .viewProduct .search-box {
    width: 100%;
    padding: 8px;
    border: 1px solid blue;
    border-radius: 5px;
}
        .viewProduct .productBoxes{
            display: grid;
            grid-template-columns: repeat(2,1fr);
            gap: 12px;
        }
        .viewProduct .productBox{
            background-color:transparent;
            box-shadow: 2px 2px 2px #CFCFCFFF;
            padding:5px 5px;
            background-blend-mode: multiply;
            /* padding: 0; */
            color: #ffffff;
            border-radius: 10px;
            /* position: relative; */
        }
        .viewProduct .text{
            position: relative;
            bottom: 0px;
            margin:3px auto;
            width: 100%;
            line-height:1.5em;
            font-weight: bold;
            background-color: blue;
            font-size: 16px;
            padding: 4px 9px ;
            color: #ffffff;
            border-radius: 9px;
        }
        .viewProduct img{
            width: 100%;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="viewProduct">
        <div class="container">
            <!-- <header>
                <button onclick="goBack()" class="back-button">←</button>
                <h2>Product</h2>
            </header> -->

            <div class="search-container">
                <input type="text" id="searchBox" placeholder="Search products..." class="search-box">
            </div>

            <div class="product-list"> 
                <h3 style="padding-left: 22px; font-size: 2em ; color:blue; margin: 0.3em 0;">Shop Affordably</h3>
                <div class="productsList" id="productsList">
                    <div class="productBoxes">
                        <?php foreach ($products as $product): ?>
                            <div class="productBox" data-id="<?php echo $product['id']; ?>" data-name="<?php echo $product['name']; ?>">
                                <div class="image">
                                <img src="screens/uploads/products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                                </div>
                                <div class="text">
                                    <div class="name"><?php echo $product['name']; ?></div>
                                    <div class="price">$<?php echo number_format($product['sale_price'], 2); ?></div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
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
            window.location.href = "marketDetails.php?id=" + productId;
        });
    });
});

    </script>
    
</body>
</html>
