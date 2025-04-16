<?php
session_start();
include '../const/db.php'; // Include database connection

// Initialize variables with default values
$customerName = '';
$contactInfo = '';
$product = '';
$quantity = 0;
$subtotal = 0;
$tax = 0;
$total = 0;
$profitLoss = 0;
$errorMessage = '';
$successMessage = '';

// Check if the form was submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $customerName = $_POST['customerName'] ?? '';
    $contactInfo = $_POST['contactInfo'] ?? '';
    $product = $_POST['product'] ?? '';
    $quantity = (int) ($_POST['quantity'] ?? 0);
    $subtotal = (float) ($_POST['subtotal'] ?? 0);
    $tax = (float) ($_POST['tax'] ?? 0);
    $total = (float) ($_POST['total'] ?? 0);
    $profitLoss = (float) ($_POST['profitLoss'] ?? 0); // Optional

    // Validate form data
    if (empty($customerName) || empty($contactInfo) || empty($product) || $quantity <= 0) {
        $errorMessage = "Please fill in all required fields.";
    } else {
        try {
            // Start a transaction to ensure atomicity
            $pdo->beginTransaction();

            // Insert the sale into the sales table
            $query = "INSERT INTO sales (customer_name, contact_info, product, quantity, subtotal, tax, total, profit) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$customerName, $contactInfo, $product, $quantity, $subtotal, $tax, $total, $profitLoss]);

            // Reduce the quantity of the product in the product table
            $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - ? WHERE name = ?");
            $stmt->execute([$quantity, $product]);

            // Commit the transaction
            $pdo->commit();

            $successMessage = 'Sale recorded successfully and product quantity updated.';
        } catch (PDOException $e) {
            // If an error occurs, roll back the transaction
            $pdo->rollBack();

            $errorMessage = 'Sale recording failed: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include("../const/head.php"); ?>
<body>
    <div class="salesM">
        <div class="container">
            <header>
                <a href="#" class="back-btn" onclick="window.history.back();">←</a>
                <span class="title">SaleProduct</span>
            </header>

            <h2>New Sale</h2>

            <?php if (isset($successMessage)): ?>
                <p style="color: green;"><?php echo $successMessage; ?></p>
            <?php elseif (isset($errorMessage)): ?>
                <p style="color: red;"><?php echo $errorMessage; ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <input type="text" name="customerName" placeholder="Enter customer name" required value="<?php echo htmlspecialchars($customerName ?? ''); ?>">
                <input type="text" name="contactInfo" placeholder="Enter contact information" required value="<?php echo htmlspecialchars($contactInfo ?? ''); ?>">
                <select name="product" required>
                    <option value="">Select a product</option>
                    <option value="Product A" <?php echo isset($product) && $product == 'Product A' ? 'selected' : ''; ?>>Product A - $10</option>
                    <option value="Product B" <?php echo isset($product) && $product == 'Product B' ? 'selected' : ''; ?>>Product B - $20</option>
                    <option value="Product C" <?php echo isset($product) && $product == 'Product C' ? 'selected' : ''; ?>>Product C - $30</option>
                </select>
                <input type="number" name="quantity" placeholder="Enter quantity" min="1" required value="<?php echo isset($quantity) ? $quantity : ''; ?>">

                <div class="summary">
                    <p>Subtotal: $<span id="subtotal"><?php echo number_format($subtotal, 2); ?></span></p>
                    <p>Tax: $<span id="tax"><?php echo number_format($tax, 2); ?></span></p>
                    <p>Profit: $<span id="profit"><?php echo number_format($profitLoss, 2); ?></span></p>
                    <p>Total: $<span id="total"><?php echo number_format($total, 2); ?></span></p>
                </div>

                <div class="buttons">
                    <button type="submit" class="submit-btn">Submit Sale</button>
                    <button type="reset" class="clear-btn">Clear</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
