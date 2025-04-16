<?php // Include database connection include('const/db.php');  // Start the session session_start();  // Check if the company name is stored in the session if (!isset($_SESSION['companyName'])) {     // Redirect to login if session is not set     header("Location: login.php");     exit(); }  // Fetch the company name from the session $companyName = $_SESSION['companyName'];  // Fetch total products $productQuery = "SELECT COUNT(*) AS total_products FROM products WHERE company = ?"; $productStmt = $conn->prepare($productQuery); $productStmt->bind_param("s", $companyName); $productStmt->execute(); $productResult = $productStmt->get_result(); $productCount = $productResult->fetch_assoc()['total_products'];  // Fetch total sales $salesQuery = "SELECT COUNT(*) AS total_sales FROM sales WHERE company = ?"; $salesStmt = $conn->prepare($salesQuery); $salesStmt->bind_param("s", $companyName); $salesStmt->execute(); $salesResult = $salesStmt->get_result(); $salesCount = $salesResult->fetch_assoc()['total_sales'];  // Close the database connection $productStmt->close(); $salesStmt->close(); $conn->close(); 
?>
<!DOCTYPE html>
<html lang="en"> <?php include("const/head.php"); ?>

<body>
    <div class="dashboard">
        <header> <button class="back-btn">&#8592;</button>
            <h2>Dashboard</h2> <a href="screens/setting.php"><i class="settings-icon fas fa-cog"></i></a>
        </header>
        <div class="loading"><?php echo $companyName; ?></div>
        <div class="profit-analysis">
            <h3>Profit Analysis</h3>
            <p class="amount">$15,237,000</p>
            <p class="subtext">From the previous week</p>
        </div>
        <div class="session-expired-cards">
            <div class="card"> <i class="fas fa-utensils"></i>
                <p> <?php echo $productCount; ?> </p> <span>Total Products</span>
            </div>
            <div class="card"> <i class="fas fa-home"></i>
                <p> <?php echo $salesCount; ?> </p> <span>Total Sales</span>
            </div>
            <div class="card full-width"> <i class="fas fa-user"></i>
                <p> 52% </p> <span>Monthly Income Growth</span>
            </div>
        </div> <?php include("const/bottomNav.php") ?>
    </div>
    <script src="scripts/index.js"></script>
</body>

</html>
in addition to what is here get the percentage of the monthly income growth or decrease based on the sales table.