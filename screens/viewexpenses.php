<?php
session_start();
include('../const/db.php');

$expenses = [];
$totalExpense = 0;
$previousMonthExpense = 0;

if (!isset($_SESSION['companyName'])) {
    die("Unauthorized access");
}

$companyName = mysqli_real_escape_string($conn, $_SESSION['companyName']);

// Fetch all expenses for the company, including ID
$query = "SELECT id, name, date, created_at, amount FROM expenses WHERE company = '$companyName' ORDER BY date DESC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $expenses[] = $row;
    }
}

// Fetch total expense for the current month
$currentMonth = date('Y-m');
$queryCurrent = "SELECT SUM(amount) AS total FROM expenses WHERE company = '$companyName' AND DATE_FORMAT(date, '%Y-%m') = '$currentMonth'";
$resultCurrent = mysqli_query($conn, $queryCurrent);
if ($row = mysqli_fetch_assoc($resultCurrent)) {
    $totalExpense = $row['total'] ?? 0;
}

// Fetch total expense for the previous month
$previousMonth = date('Y-m', strtotime('-1 month'));
$queryPrev = "SELECT SUM(amount) AS total FROM expenses WHERE company = '$companyName' AND DATE_FORMAT(date, '%Y-%m') = '$previousMonth'";
$resultPrev = mysqli_query($conn, $queryPrev);
if ($rowPrev = mysqli_fetch_assoc($resultPrev)) {
    $previousMonthExpense = $rowPrev['total'] ?? 0;
}

// Calculate percentage change
$percentageChange = 0;
if ($previousMonthExpense > 0) {
    $percentageChange = (($totalExpense - $previousMonthExpense) / $previousMonthExpense) * 100;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Report</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin: auto;
        }
        .header {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header i {
            font-size: 18px;
            cursor: pointer;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
        }
        .search-filter-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            gap: 10px;
        }
        .search-container {
            flex: 1;
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 8px;
            background: white;
        }
        .search-container i {
            color: #888;
            margin-right: 8px;
        }
        .search-bar {
            width: 100%;
            border: none;
            outline: none;
            font-size: 14px;
        }
        .filter-icon {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: white;
            cursor: pointer;
        }
        .filter-container {
            display: none;
            flex-direction: column;
            gap: 5px;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .filter-container div {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 8px;
            background: white;
        }
        .filter-container input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 14px;
        }
        .apply-filters {
            width: 100%;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 5px;
        }
        .expense-summary {
            text-align: center;
            margin-top: 15px;
        }
        .expense-summary h3 {
            margin: 5px 0;
            color: #28a745;
            font-size: 24px;
            font-weight: bold;
        }
        .expense-summary small {
            color: gray;
            font-size: 12px;
        }
        .expense-list {
            margin-top: 10px;
        }
        .expense-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .expense-item:last-child {
            border-bottom: none;
        }
        .expense-name {
            font-weight: bold;
        }
        .expense-time {
            color: gray;
            font-size: 12px;
        }
        .expense-amount {
            color: #ff9900;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header">
        <i class="fas fa-arrow-left" onclick="window.history.back();"></i>
        <h2>Expenses Report</h2>
    </div>

    <!-- Search & Filter -->
    <div class="search-filter-container">
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" class="search-bar" id="search" placeholder="Search by expense name">
        </div>
        <div class="filter-icon" onclick="toggleFilters()">
            <i class="fas fa-filter"></i>
        </div>
    </div>

    <!-- Date Filters -->
    <div class="filter-container" id="filterContainer">
        <div>
            <i class="fas fa-calendar-alt"></i>
            <input type="date" id="startDate">
        </div>
        <div>
            <i class="fas fa-calendar-alt"></i>
            <input type="date" id="endDate">
        </div>
        <button class="apply-filters" onclick="applyFilters()">Apply Filters</button>
    </div>

    <!-- Total Expense Summary -->
    <div class="expense-summary">
        <h3 id="totalExpense">$<?php echo number_format($totalExpense, 2); ?></h3>
        <small id="expenseChange">
            <?php echo ($percentageChange >= 0 ? '+' : '') . number_format($percentageChange, 2); ?>% compared to last month
        </small>
    </div>

    <!-- Expenses List -->
    <div class="expense-list" id="expenseList">
        <?php foreach ($expenses as $expense): ?>
            <div class="expense-item" 
                data-id="<?php echo $expense['id']; ?>"
                data-name="<?php echo strtolower($expense['name']); ?>" 
                data-date="<?php echo $expense['date']; ?>" 
                onclick="goToExpenseDetails(<?php echo $expense['id']; ?>)">
                <div>
                    <div class="expense-name"><?php echo htmlspecialchars($expense['name']); ?></div>
                    <div class="expense-time"><?php echo htmlspecialchars($expense['created_at']); ?></div>
                </div>
                <div class="expense-amount">$<?php echo number_format($expense['amount'], 2); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function toggleFilters() {
        var filterContainer = document.getElementById('filterContainer');
        filterContainer.style.display = (filterContainer.style.display === 'none' || filterContainer.style.display === '') ? 'flex' : 'none';
    }

    function applyFilters() {
        var startDate = document.getElementById('startDate').value;
        var endDate = document.getElementById('endDate').value;
        var expenseItems = document.querySelectorAll('.expense-item');

        expenseItems.forEach(function(item) {
            var expenseDate = item.getAttribute('data-date');
            if ((startDate && expenseDate < startDate) || (endDate && expenseDate > endDate)) {
                item.style.display = 'none';
            } else {
                item.style.display = 'flex';
            }
        });
    }

    document.getElementById('search').addEventListener('input', function(e) {
        var searchText = e.target.value.toLowerCase();
        var expenseItems = document.querySelectorAll('.expense-item');

        expenseItems.forEach(function(item) {
            var expenseName = item.getAttribute('data-name');
            if (expenseName.indexOf(searchText) !== -1) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    function goToExpenseDetails(expenseId) {
        window.location.href = "expenceDetails.php?id=" + expenseId;
    }
</script>

</body>
</html>
