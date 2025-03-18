<?php
session_start();
include '../const/db.php'; // Include database connection

// Handle product submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Validate input fields
        if (!isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['salePrice'], $_POST['category'], $_POST['quantity']) ||
            empty($_POST['name']) || empty($_POST['description']) || !is_numeric($_POST['price']) || 
            !is_numeric($_POST['salePrice']) || empty($_POST['category']) || !is_numeric($_POST['quantity'])) {
            throw new Exception('All fields are required and must be valid.');
        }

        // Sanitize input
        $name = htmlspecialchars(trim($_POST['name']));
        $description = htmlspecialchars(trim($_POST['description']));
        $price = floatval($_POST['price']);
        $salePrice = floatval($_POST['salePrice']);
        $category = htmlspecialchars(trim($_POST['category']));
        $quantity = intval($_POST['quantity']);

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);

            // Validate file type (only allow images)
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($imageExtension), $allowedExtensions)) {
                throw new Exception("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
            }

            // Create upload directory if not exists
            $uploadDir = 'uploads/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate a unique filename and move the file
            $imagePath = $uploadDir . uniqid('product_', true) . '.' . $imageExtension;
            if (!move_uploaded_file($imageTmpPath, $imagePath)) {
                throw new Exception("Failed to upload image.");
            }
        }

        // Insert product into database
        $stmt = $pdo->prepare("
            INSERT INTO products (name, description, price, sale_price, category, quantity, image)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $description, $price, $salePrice, $category, $quantity, $imagePath]);

        $_SESSION['success_message'] = "Product added successfully!";
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include("../const/head.php"); ?>
<body>
<div class="addProduct">
    <div class="container">
        <div class="header">
            <button class="back-btn" onclick="window.history.back();"> 
                <i class="fa-solid fa-arrow-left" class="back-btn"></i>
            </button>
            Add Product
        </div>
        <div class="form-group">
            <?php
            if (isset($_SESSION['error_message'])) {
                echo "<p style='color: red;'>" . $_SESSION['error_message'] . "</p>";
                unset($_SESSION['error_message']);
            }
            if (isset($_SESSION['success_message'])) {
                echo "<p style='color: green;'>" . $_SESSION['success_message'] . "</p>";
                unset($_SESSION['success_message']);
            }
            ?>
            <form  method="post" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Product Name" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <div class="price-container">
                    <input type="number" name="price" placeholder="Price" step="0.01" required>
                    <input type="number" name="salePrice" placeholder="Sale Price" step="0.01" required>
                </div>
                <div class="category-container">
                    <input type="text" name="category" placeholder="Category" required>
                    <div class="quantity">
                        <button type="button" onclick="decrement()">-</button>
                        <input type="number" id="count" name="quantity" value="1" min="1" required>
                        <button type="button" onclick="increment()">+</button>
                    </div>
                </div>
                <input type="file" name="image" accept="image/*">
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </div>
</div>

<script>
    function increment() {
        let countElement = document.getElementById("count");
        countElement.value = parseInt(countElement.value) + 1;
    }
    function decrement() {
        let countElement = document.getElementById("count");
        if (parseInt(countElement.value) > 1) {
            countElement.value = parseInt(countElement.value) - 1;
        }
    }
</script>

</body>
</html>
