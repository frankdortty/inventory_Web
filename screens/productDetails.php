<?php
session_start();
include('../const/db.php');

// Handle product deletion if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];

    // Fetch image filename
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    // Delete product from database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    if ($stmt->execute()) {
        // Delete image from folder
        $imagePath = __DIR__ . '/uploads/products/' . $image;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $stmt->close();
        $conn->close();
        header("Location: viewProduct.php?deleted=1");
        exit;
    } else {
        echo "Failed to delete product.";
    }
    $stmt->close();
}

// Load product details for display
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
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
        :root {
            --primary: #6c5ce7;
            --primary-dark: #5649c0;
            --secondary: #00cec9;
            --dark: #2d3436;
            --light: #f5f6fa;
            --danger: #d63031;
            --danger-dark: #b52b2b;
            --success: #00b894;
            --success-dark: #00997b;
            --glass: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--dark);
            padding: 20px;
        }

        .product-details {
            max-width: 1200px;
            margin: 30px auto;
            background: var(--glass);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow);
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .container {
            padding: 30px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--glass-border);
        }

        .back-button {
            background: var(--light);
            color: var(--primary);
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 50px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .product-info {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 40px;
            gap: 30px;
        }

        .product-image-container {
            flex: 1;
            min-width: 300px;
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.5s ease;
        }

        .product-image-container:hover {
            transform: scale(1.02);
        }

        .product-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            display: block;
        }

        .product-details-text {
            flex: 1;
            min-width: 300px;
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 15px;
            backdrop-filter: blur(5px);
        }

        .product-details-text h3 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: var(--primary);
            position: relative;
            padding-bottom: 10px;
        }

        .product-details-text h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--secondary);
            border-radius: 3px;
        }

        .detail-item {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }

        .detail-label {
            font-weight: 600;
            color: var(--dark);
            min-width: 120px;
            display: inline-block;
        }

        .detail-value {
            flex: 1;
            color: #555;
            line-height: 1.6;
        }

        .price-highlight {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
            background: rgba(108, 92, 231, 0.1);
            padding: 5px 10px;
            border-radius: 8px;
            display: inline-block;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            margin-top: 30px;
        }

        .edit-button, .delete-button {
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .edit-button {
            background: var(--success);
            color: white;
        }

        .edit-button:hover {
            background: var(--success-dark);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 184, 148, 0.3);
        }

        .delete-button {
            background: var(--danger);
            color: white;
        }

        .delete-button:hover {
            background: var(--danger-dark);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(214, 48, 49, 0.3);
        }

        /* Futuristic floating elements */
        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            pointer-events: none;
        }

        .circle-1 {
            width: 100px;
            height: 100px;
            top: -30px;
            right: -30px;
        }

        .circle-2 {
            width: 60px;
            height: 60px;
            bottom: -20px;
            left: -20px;
        }

        /* Holographic effect */
        .holographic {
            position: relative;
            overflow: hidden;
        }

        .holographic::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0) 45%,
                rgba(255, 255, 255, 0.5) 50%,
                rgba(255, 255, 255, 0) 55%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(30deg);
            animation: holographic 6s infinite linear;
        }

        @keyframes holographic {
            0% { transform: translateX(-100%) rotate(30deg); }
            100% { transform: translateX(100%) rotate(30deg); }
        }

        /* Futuristic toast notification */
        #toast {
            visibility: hidden;
            min-width: 300px;
            background: rgba(0, 184, 148, 0.9);
            backdrop-filter: blur(10px);
            color: white;
            text-align: center;
            border-radius: 12px;
            padding: 20px;
            position: fixed;
            z-index: 999;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
            font-size: 16px;
            box-shadow: 0 10px 25px rgba(0, 184, 148, 0.3);
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s, visibility 0.3s;
        }

        #toast.show {
            visibility: visible;
            opacity: 1;
            animation: floatUp 0.5s ease-out;
        }

        #toast::before {
            content: '✓';
            font-size: 20px;
            font-weight: bold;
        }

        @keyframes floatUp {
            from { bottom: 10px; opacity: 0; }
            to { bottom: 30px; opacity: 1; }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .product-info {
                flex-direction: column;
            }
            
            .product-image-container, .product-details-text {
                min-width: 100%;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .edit-button, .delete-button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="floating-circle circle-1"></div>
    <div class="floating-circle circle-2"></div>

    <div class="product-details holographic">
        <div class="container">
            <header>
                <button onclick="goBack()" class="back-button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Back
                </button>
                <h2 style="color: white;">Product Details</h2>
            </header>

            <div class="product-info">
                <div class="product-image-container">
                    <img src="./uploads/products/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                </div>
                <div class="product-details-text">
                    <h3><?php echo $product['name']; ?></h3>
                    
                    <div class="detail-item">
                        <span class="detail-label">Description:</span>
                        <span class="detail-value"><?php echo $product['description']; ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Cost Price:</span>
                        <span class="detail-value price-highlight">$<?php echo number_format($product['price'], 2); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Sales Price:</span>
                        <span class="detail-value price-highlight">$<?php echo number_format($product['sale_price'], 2); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Category:</span>
                        <span class="detail-value"><?php echo $product['category']; ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Quantity:</span>
                        <span class="detail-value"><?php echo $product['quantity']; ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Created At:</span>
                        <span class="detail-value"><?php echo date('F j, Y, g:i a', strtotime($product['created_at'])); ?></span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Updated At:</span>
                        <span class="detail-value"><?php echo date('F j, Y, g:i a', strtotime($product['updated_at'])); ?></span>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <button class="edit-button" onclick="window.location.href = 'editProduct.php?id=<?php echo $product['id']; ?>'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit
                </button>

                <form method="POST" onsubmit="return confirmDelete();">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" name="delete_product" class="delete-button">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Futuristic Toast Notification -->
    <div id="toast">Product deleted successfully</div>

    <script>
        function goBack() {
            window.history.back();
        }

        function confirmDelete() {
            return confirm('Are you sure you want to delete this product? This action cannot be undone.');
        }

        // Show holographic toast if redirected with ?deleted=1
        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('deleted') === '1') {
                const toast = document.getElementById("toast");
                toast.classList.add("show");
                
                setTimeout(() => {
                    toast.classList.remove("show");
                    // Remove ?deleted=1 from URL
                    window.history.replaceState(null, null, window.location.pathname);
                }, 3000);
            }
            
            // Create floating circles
            createFloatingElements();
        }
        
        function createFloatingElements() {
            const container = document.querySelector('.product-details');
            
            for (let i = 0; i < 5; i++) {
                const circle = document.createElement('div');
                circle.className = 'floating-circle';
                
                // Random size between 20px and 80px
                const size = Math.floor(Math.random() * 60) + 20;
                circle.style.width = `${size}px`;
                circle.style.height = `${size}px`;
                
                // Random position within container
                const left = Math.random() * 80 + 10; // 10% to 90%
                const top = Math.random() * 80 + 10;
                circle.style.left = `${left}%`;
                circle.style.top = `${top}%`;
                
                // Random blur and opacity
                const blur = Math.random() * 5 + 2;
                const opacity = Math.random() * 0.3 + 0.1;
                circle.style.filter = `blur(${blur}px)`;
                circle.style.opacity = opacity;
                
                // Random animation
                const duration = Math.random() * 10 + 10;
                circle.style.animation = `float ${duration}s infinite ease-in-out`;
                
                container.appendChild(circle);
            }
        }
    </script>
</body>
</html>