<?php
session_start();  // Start the session

include('../const/db.php');

// Ensure the form is submitted using POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect form data and sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float) $_POST['price']; // Ensure price is treated as a float
    $salePrice = (float) $_POST['salePrice']; // Ensure salePrice is treated as a float
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $quantity = (int) $_POST['quantity']; // Ensure quantity is treated as an integer

    // Retrieve company name from session
    $companyName = isset($_SESSION['companyName']) ? $_SESSION['companyName'] : 'Default Company';  // Use fallback if not set
    $companyName = isset($_SESSION['companyName']) ? $_SESSION['companyName'] : 'Default Company';  // Use fallback if not set
    // Handle file upload
    $image = $_FILES['image'];
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    $imageError = $image['error'];
    $imageSize = $image['size'];

    // Define the upload directory and allowed file types
    $uploadDir = './uploads/products/';
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $imageNewName = uniqid('', true) . '.' . $imageExtension;
    $imageDestination = $uploadDir . $imageNewName;

    // Check if the product name already exists in the database
    $checkQuery = $conn->prepare("SELECT * FROM products WHERE name = ?");
    $checkQuery->bind_param('s', $name);
    $checkQuery->execute();
    $checkQueryResult = $checkQuery->get_result();

    if ($checkQueryResult->num_rows > 0) {
        // Product already exists in the database
        $message = "A product with this name already exists.";
    } else {
        // Validate and handle file upload
        if ($imageError === 0) {
            if ($imageSize <= 5000000) { // Max file size is 5MB
                if (in_array($imageExtension, $allowedExtensions)) {
                    if (move_uploaded_file($imageTmpName, $imageDestination)) {
                        // Insert product details into the database using MySQLi
                        $stmt = $conn->prepare("INSERT INTO products (name, description, price, sale_price, category, quantity, image, company)
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param('ssddsiss', $name, $description, $price, $salePrice, $category, $quantity, $imageNewName, $companyName);
                        $stmt->execute();

                        // Check if the insertion was successful
                        if ($stmt->affected_rows > 0) {
                            $message = "Product added successfully!";
                        } else {
                            $message = "Error inserting product into the database.";
                        }

                        // Close the statement
                        $stmt->close();
                    } else {
                        $message = "Error uploading the file.";
                    }
                } else {
                    $message = "Invalid file type. Only JPG, PNG, GIF files are allowed.";
                }
            } else {
                $message = "File size is too large. Maximum size is 5MB.";
            }
        } else {
            $message = "Error uploading the image.";
        }
    }
    // Close the check query
    $checkQuery->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include("../const/head.php") ?>
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
                <form method="post" enctype="multipart/form-data">
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
                    <input type="file" name="image" accept="image/*" required>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Popup -->
    <?php if (!empty($message)): ?>
    <div id="popupModal" class="popup-modal" style="display: block;">
        <div class="modal-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <p><?php echo $message; ?></p>
            <button onclick="window.location.href ='./addProduct.php'">Continue</button>
        </div>
    </div>
    <?php endif; ?>

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

        // Close the popup modal
        function closePopup() {
            document.getElementById("popupModal").style.display = "none";
        }
    </script>

    <style>
        /* Popup Modal Styles */
        .popup-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 5em;
        }
        .modal-content {
            background-color: #fff;
        }
    </style>
</html>
