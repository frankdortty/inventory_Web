<?php
include '../const/db.php';

try {
    // Prepare and execute SQL query to fetch all products
    $stmt = $pdo->prepare("SELECT id, name, description, price, sale_price, category, quantity, image FROM products");
    $stmt->execute();

    // Fetch all products as an associative array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any products are found
    if ($products) {
        $productData = json_encode(["success" => true, "data" => $products]);
    } else {
        $productData = json_encode(["success" => false, "error" => "No products found."]);
    }
} catch (PDOException $e) {
    $productData = json_encode(["success" => false, "error" => "Database error: " . $e->getMessage()]);
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include("../const/head.php")?>
<body>
    <div class="viewProduct">
        <div class="container">
            <header>
                <button onclick="goBack()" class="back-button">←</button>
                <h2>Product</h2>
            </header>

            <div class="search-container">
                <input type="text" id="searchBox" placeholder="Search..." class="search-box">
                <button id="searchButton" class="search-button">Search</button>
            </div>
            <div class="product-list">
                <h3>Best Sale Product</h3>
                <div class="productsList" id="productsList">
                    <div class="productBoxes">
                        <?php
                        // Decode product data to loop over the products
                        $decodedProducts = json_decode($productData, true);
                        if ($decodedProducts['success'] && !empty($decodedProducts['data'])):
                            foreach ($decodedProducts['data'] as $product):
                        ?>
                             <div class="productBox" data-name="<?php echo strtolower($product['name']); ?>" style="background-image: url('<?php echo $product['image']; ?>');">
                                <div class="text">
                                    <div class="name"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <div class="price"><?php echo number_format($product['sale_price'], 2); ?></div>
                                </div>
                            </div>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <p>No products available.</p>
                        <?php endif; ?>
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

        // Frontend search functionality
        document.getElementById("searchButton").addEventListener("click", function() {
            const searchQuery = document.getElementById("searchBox").value.toLowerCase();
            const productBoxes = document.querySelectorAll(".productBox");

            productBoxes.forEach(function(box) {
                const productName = box.getAttribute("data-name");
                if (productName.includes(searchQuery)) {
                    box.style.display = "block";
                } else {
                    box.style.display = "none";
                }
            });
        });
    </script>
</body>
</html>
