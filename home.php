<!DOCTYPE html>
<html lang="en">
<?php include("const/head.php")?>
<body>
    <div class="homePage">
        <div class="container">
            <div class="app-title">
                <h2>INVENTORY PRO</h2>
            </div>

            <!-- Menu Grid -->
            <div class="grid-menu">
                <div class="menu-item" onclick="window.location.href ='./screens/addProduct.php' ">
                    <i class="fas fa-plus-circle"></i>
                    <p>Add New Product</p>
                </div>
                <div class="menu-item" onclick="window.location.href ='./screens/viewProduct.php' ">
                    <i class="fas fa-box"></i>
                    <p>View Product</p>
                </div>
                <div class="menu-item" onclick="window.location.href ='./screens/sales.php' ">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Make Sales</p>
                </div>
                <div class="menu-item"  onclick="window.location.href ='./screens/viewsales.php' ">
                    <i class="fas fa-chart-bar"></i>
                    <p>Sales Report</p>
                </div>
                <div class="menu-item"  onclick="window.location.href ='./screens/expenses.php'">
                    <i class="fas fa-dollar-sign"></i>
                    <p>Store Expenses</p>
                </div>
                <div class="menu-item" onclick="window.location.href ='./screens/viewexpenses.php'">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <p>Expense Report</p>
                </div>
                <div class="menu-item">
                    <i class="fas fa-exchange-alt"></i>
                    <p>Transactions</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Container -->

    <!-- Bottom Navigation -->
   <?php include("const/bottomNav.php") ?>
   <script src="scripts/index.js"></script>
</body>
</html>
