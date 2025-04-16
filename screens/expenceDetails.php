<?php
session_start();
include('../const/db.php');

if (!isset($_SESSION['companyName'])) {
    die("Unauthorized access");
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$expenseId = intval($_GET['id']);
$companyName = mysqli_real_escape_string($conn, $_SESSION['companyName']);

$query = "SELECT * FROM expenses WHERE id = $expenseId AND company = '$companyName'";
$result = mysqli_query($conn, $query);
$expense = mysqli_fetch_assoc($result);

if (!$expense) {
    die("Expense not found");
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Details</title>
</head>
<body>
    <h2>Expense Details</h2>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($expense['name']); ?></p>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($expense['date']); ?></p>
    <p><strong>Amount:</strong> $<?php echo number_format($expense['amount'], 2); ?></p>
    <p><strong>Created At:</strong> <?php echo htmlspecialchars($expense['created_at']); ?></p>
    <a href="viewexpenses.php">Back to Expenses</a>
</body>
</html>
