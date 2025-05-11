<?php
session_start();
include('const/db.php');

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

    // Format price with currency symbol
    $formattedPrice = '₦' . number_format($product['price'], 2);
    
    // Format date (e.g., "3 hours ago" or "May 15, 2023")
    $createdDate = new DateTime($product['created_at']);
    $currentDate = new DateTime();
    $interval = $currentDate->diff($createdDate);
    
    if ($interval->days < 1) {
        if ($interval->h < 1) {
            $timeAgo = $interval->i . ' minutes ago';
        } else {
            $timeAgo = $interval->h . ' hours ago';
        }
    } else {
        $timeAgo = $createdDate->format('M j, Y');
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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Car Listing</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: blue;
            --secondary-color: #f5f5f5;
            --text-color: #333;
            --light-text: #666;
            --border-color: #eee;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-decoration: none;
        }
        
        body {
            background-color: #f9f9f9;
            color: var(--text-color);
            line-height: 1.6;
            /* padding: 20px; */
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .container:hover {
            transform: translateY(-2px);
        }
        
        .car-image-container {
            position: relative;
            width: 100%;
            height: 300px;
            overflow: hidden;
        }
        
        .car-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .car-image:hover {
            transform: scale(1.03);
        }
        
        .header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .location {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: var(--light-text);
            margin-bottom: 8px;
        }
        
        .location i {
            font-size: 12px;
        }
        
        .seller {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--text-color);
        }
        
        .car-info {
            padding: 20px;
        }
        
        .car-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #222;
        }
        
        .price-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .price {
            font-size: 26px;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .negotiable {
            padding: 4px 10px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .fixed {
            background-color: #e74c3c;
        }
        
        .action-buttons {
            display: flex;
            gap: 12px;
            margin: 20px 0;
        }
        
        .btn {
            padding: 12px 15px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            flex: 1;
            text-align: center;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .btn-call {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-request {
            background-color: var(--secondary-color);
            color: var(--text-color);
        }
        
        .market-price {
            font-size: 15px;
            color: var(--light-text);
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            line-height: 1.7;
        }
        
        .chat-section {
            padding: 20px;
            border-top: 1px solid var(--border-color);
            background-color: #fafafa;
        }
        
        .chat-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 18px;
        }
        
        .chat-title {
            font-weight: 700;
            font-size: 18px;
            color: #222;
        }
        
        .quick-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .quick-btn {
            padding: 8px 14px;
            background-color: var(--secondary-color);
            border: none;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .quick-btn:hover {
            background-color: #e0e0e0;
        }
        
        .message-box {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            resize: none;
            height: 100px;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }
        
        .message-box:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }
        
        .start-chat-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }
           
        .start-chat-btn:hover {
            background-color: #000fff;
            transform: scaleY(90%);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 480px) {
            .car-image-container {
                height: 250px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .quick-actions {
                justify-content: center;
            }
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

    </style>
</head>
<body>
    <div class="container">
    <header>
        <button onclick="window.history.back()" class="back-button">← Back</button>
    </header>
        <!-- Car Image Section -->
        <div class="car-image-container">
            <img src="screens/uploads/products/<?php echo htmlspecialchars($product['image']); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                 class="car-image"
                 onerror="this.src='https://via.placeholder.com/600x300?text=Car+Image+Not+Available'">
        </div>
        
        <div class="header">
            <div class="location">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo htmlspecialchars($product['location'] ?? 'Location not specified'); ?>, 
                <?php echo $timeAgo; ?>
            </div>
            <div class="seller">
                <i class="fas fa-store"></i> <?php echo htmlspecialchars($product['company']); ?>
            </div>
        </div>
        
        <div class="car-info">
            <h1 class="car-title"><?php echo htmlspecialchars($product['name']); ?></h1>
            
            <div class="price-container">
                <div class="price"><?php echo $formattedPrice; ?></div>
                <div class="negotiable <?php echo ($product['negotiable'] ?? false) ? '' : 'fixed'; ?>">
                    <?php echo ($product['negotiable'] ?? false) ? 'Negotiable' : 'Fixed'; ?>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="#" class="btn btn-request">
                    <i class="fas fa-phone-alt"></i> Request call back
                </a>
                <a href="tel:<?php echo htmlspecialchars($product['phone'] ?? ''); ?>" class="btn btn-call">
                    <i class="fas fa-phone"></i> Call Seller
                </a>
            </div>
            
            <div class="market-price">
                <h3><i class="fas fa-info-circle"></i> Description</h3>
                <p><?php echo nl2br(htmlspecialchars($product['description'] ?? 'No description provided')); ?></p>
                
                <?php if (!empty($product['market_price'])): ?>
                <div style="margin-top: 15px;">
                    <strong>Market price range:</strong> 
                    <?php echo htmlspecialchars($product['market_price']); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="chat-section">
            <div class="chat-header">
                <div class="chat-title">
                    <i class="fas fa-comments"></i> Chat with the seller
                </div>
            </div>
            
            <div class="quick-actions">
                <button class="quick-btn">Make an offer</button>
                <button class="quick-btn">Is this available?</button>
                <button class="quick-btn">Last price</button>
                <button class="quick-btn">More details</button>
            </div>
            
            <textarea class="message-box" placeholder="Write your message here..."></textarea>
            <button class="start-chat-btn">
                <i class="fas fa-paper-plane"></i> Start Chat
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced call button functionality
            document.querySelector('.btn-call').addEventListener('click', function(e) {
                if (!this.getAttribute('href').startsWith('tel:')) {
                    e.preventDefault();
                    alert('Phone number not available');
                }
            });
            
            // Request callback button
            document.querySelector('.btn-request').addEventListener('click', function(e) {
                e.preventDefault();
                const phone = prompt('Please enter your phone number for callback:');
                if (phone) {
                    alert('Callback requested. The seller will contact you at ' + phone);
                }
            });
            
            // Quick action buttons
            const quickButtons = document.querySelectorAll('.quick-btn');
            quickButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const messageBox = document.querySelector('.message-box');
                    messageBox.value = this.textContent.trim() + ' ';
                    messageBox.focus();
                });
            });
            
            // Start chat button with validation
            document.querySelector('.start-chat-btn').addEventListener('click', function() {
                const message = document.querySelector('.message-box').value.trim();
                if (message) {
                    // In a real app, this would send to server
                    alert('Message sent to seller:\n\n' + message);
                    document.querySelector('.message-box').value = '';
                } else {
                    alert('Please write a message before sending.');
                    document.querySelector('.message-box').focus();
                }
            });
            
            // Add animation to buttons when clicked
            const buttons = document.querySelectorAll('button, .btn');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 200);
                });
            });
        });
    </script>
</body>
</html>