<?php 
    session_start();
    include('../const/db.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $explain = mysqli_real_escape_string($conn, $_POST['explain']);
        $amount = floatval($_POST['amount']); // Ensure it's numeric
        $date = mysqli_real_escape_string($conn, $_POST['date']);

        $companyName = isset($_SESSION['companyName']) ? mysqli_real_escape_string($conn, $_SESSION['companyName']) : "Unknown";

        $query = "INSERT INTO expenses (company, name, explains, amount, date) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssds", $companyName, $name, $explain, $amount, $date);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['successMessage'] = "Expense saved successfully";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['errorMessage'] = "Error saving expense";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }

    // Fetch session messages
    $successMessage = isset($_SESSION['successMessage']) ? $_SESSION['successMessage'] : null;
    $errorMessage = isset($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : null;
    unset($_SESSION['successMessage'], $_SESSION['errorMessage']);
?>

<!DOCTYPE html>
<html lang="en">
<?php include("../const/head.php") ?>
<body>
    <div class="expensesM">
        <div class="container">
            <header>
                <a href="#" class="back-btn" onclick="window.history.back();">←</a>
                <span class="title">StoreExpenses</span>
            </header>

            <form method="POST" id="expenseForm">
                <input type="text" name="name" id="itemName" placeholder="Item Name" required>
                <input type="text" name="explain" id="explanation" placeholder="Explain" required>
                <input type="number" name="amount" id="amount" placeholder="Amount" step="0.01" required>
                <input type="date" name="date" id="date" required>
                <button type="submit" class="save-btn">Save</button>
            </form>

            <?php if ($successMessage): ?>
                <div class="message success" id="responseMessage">
                    <p><?php echo htmlspecialchars($successMessage); ?></p>
                </div>
            <?php elseif ($errorMessage): ?>
                <div class="message error" id="responseMessage">
                    <p><?php echo htmlspecialchars($errorMessage); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="popup" class="popup" style="display: none;">
        <div class="popup-content">
            <p id="popupMessage"></p>
            <button onclick="closePopup()">Close</button>
        </div>
    </div>

    <script>
        function showSuccessMessage() {
            document.getElementById('popupMessage').innerText = 'Expense saved successfully';
            document.getElementById('popup').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
            window.location.href = '../home.php';
        }

        window.onload = function() {
            <?php if ($successMessage): ?>
                showSuccessMessage();
            <?php endif; ?>
        };
    </script>

    <style>
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 300px;
        }

        .popup button {
            margin-top: 10px;
        }
    </style>
</body>
</html>
