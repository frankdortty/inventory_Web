<?php 
// Include database connection
include('const/db.php');  

// Start the session
session_start();  

// Check if the company name is stored in the session
if (!isset($_SESSION['companyName'])) {     
    // Redirect to login if session is not set     
    header("Location: login.php");     
    exit(); 
}  

// Fetch the company name from the session
$companyName = $_SESSION['companyName'];  

// Fetch total products
$productQuery = "SELECT COUNT(*) AS total_products FROM products WHERE company = ?";
$productStmt = $conn->prepare($productQuery);
$productStmt->bind_param("s", $companyName);
$productStmt->execute();
$productResult = $productStmt->get_result();
$productCount = $productResult->fetch_assoc()['total_products'];  

// Fetch total sales for the current month
$currentMonthQuery = "SELECT SUM(total) AS total_sales_current_month FROM sales WHERE company = ? AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
$currentMonthStmt = $conn->prepare($currentMonthQuery);
$currentMonthStmt->bind_param("s", $companyName);
$currentMonthStmt->execute();
$currentMonthResult = $currentMonthStmt->get_result();
$currentMonthSales = $currentMonthResult->fetch_assoc()['total_sales_current_month'] ?? 0;  

// Fetch total sales for the previous month
$previousMonthQuery = "SELECT SUM(total) AS total_sales_previous_month FROM sales WHERE company = ? AND MONTH(created_at) = MONTH(CURRENT_DATE()) - 1 AND YEAR(created_at) = YEAR(CURRENT_DATE())";
$previousMonthStmt = $conn->prepare($previousMonthQuery);
$previousMonthStmt->bind_param("s", $companyName);
$previousMonthStmt->execute();
$previousMonthResult = $previousMonthStmt->get_result();
$previousMonthSales = $previousMonthResult->fetch_assoc()['total_sales_previous_month'] ?? 0;

// Calculate the monthly income growth or decrease
if ($previousMonthSales > 0) {
    $growthPercentage = (($currentMonthSales - $previousMonthSales) / $previousMonthSales) * 100;
} else {
    $growthPercentage = $currentMonthSales > 0 ? 100 : 0; // If there's no previous month sales, consider it as 100% growth or 0 if no sales at all
}

$salesQuery = "SELECT COUNT(*) AS total_sales FROM sales WHERE company = ?"; $salesStmt = $conn->prepare($salesQuery); $salesStmt->bind_param("s", $companyName); $salesStmt->execute(); $salesResult = $salesStmt->get_result(); $salesCount = $salesResult->fetch_assoc()['total_sales']; 
// Close the database connection
$productStmt->close();
$currentMonthStmt->close();
$previousMonthStmt->close();
$conn->close();


?> 

<!DOCTYPE html>
<html lang="en">
<?php include("const/head.php"); ?>
<body>
    <div class="dashboard">
        <header>
            <button class="back-btn">&#8592;</button>
            <h2>Dashboard</h2>
            <a href="screens/setting.php"><i class="settings-icon fas fa-cog"></i></a>
        </header>

        <div class="loading" style="display:flex; gap:22px; align-items:center ">
            <div class="image" style="border-radius:50% background-color:blue,"> <img src="media/send.jpg" style="border-radius:50%" width="60px" alt="Profile Image">  </div>
            <div class="text">Hi, <?php echo $companyName; ?> </div>
        </div>

        <div class="profit-analysis">
            <h3>Profit Analysis</h3>
            <p class="amount">$15,237,000</p>
            <p class="subtext">From the previous week</p>
        </div>

        <div class="session-expired-cards">
           <div class="card">
                <a href="screens/viewProduct.php" style="color:black">
                    <i class="fas fa-utensils"></i>
                    <p> <?php echo $productCount; ?> </p>
                    <span>Total Products</span>
                </a>
            </div>
            <div class="card">
                <a href="screens/viewsales.php" style="color:black">
                    <i class="fa-solid fa-chart-line"></i>
                    <p> <?php echo $salesCount; ?> </p>
                    <span>Total Sales</span>
                </a>
            </div>
            <div class="card full-width">
                <i class="fas fa-user"></i>
                <p> <?php echo round($growthPercentage, 2); ?>% </p>
                <span>Monthly Income Growth</span>
            </div>
        </div>

        <?php include("const/bottomNav.php") ?>
    </div>
    <script src="scripts/index.js"></script>
</body>
</html>
