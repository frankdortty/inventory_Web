<?php 
include '../const/db.php'; // Include database connection

// Initialize response array
$response = [
    'status' => 'error',
    'message' => ''
];

// Check if the form is submitted via POST and necessary data is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['explain'], $_POST['amount'], $_POST['date'])
    && !empty($_POST['name']) && !empty($_POST['explain']) && !empty($_POST['amount']) && !empty($_POST['date'])) {

    // Sanitize and assign input values
    $name = $_POST['name'];
    $explain = $_POST['explain'];
    $amount = (float) $_POST['amount']; // Cast to float for amount
    $date = $_POST['date'];

    try {
        // Prepare the SQL insert statement
        $stmt = $pdo->prepare("INSERT INTO expenses (name, explains, amount, date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $explain, $amount, $date]);

        // On success
        $response = [
            'status' => 'success',
            'message' => 'Expense saved successfully'
        ];
    } catch (PDOException $e) {
        // Handle any database errors
        $response = [
            'status' => 'error',
            'message' => 'Error saving expense: ' . $e->getMessage()
        ];
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If fields are missing or empty
    $response = [
        'status' => 'error',
        'message' => 'All fields are required and must not be empty'
    ];
}

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

            <!-- Form for expense data -->
            <form method="POST" id="expenseForm">
                <input type="text" name="name" id="itemName" placeholder="Item Name" required>
                <input type="text" name="explain" id="explanation" placeholder="Explain" required>
                <input type="number" name="amount" id="amount" placeholder="Amount" required>
                <input type="date" name="date" id="date" required>

                <button type="submit" class="save-btn">Save</button>
            </form>

            <!-- Message to show after submission -->
            <?php if (isset($response['message'])): ?>
                <div class="message <?= $response['status'] ?>" id="responseMessage">
                    <p><?= $response['message'] ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Popup Modal for Success -->
    <div id="popup" class="popup" style="display: none;">
        <div class="popup-content">
            <p id="popupMessage"></p>
            <button onclick="closePopup()">Close</button>
           
        </div>
    </div>

    <script>
        // Show popup if there's a success message
        <?php if ($response['status'] === 'success'): ?>
            document.getElementById('popupMessage').innerText = 'Expense saved successfully';
            document.getElementById('popup').style.display = 'block';
        <?php endif; ?>

        // Close the popup when the user clicks the button
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
             window.location.href ='../home.php'
        }
    </script>

    <style>
        /* Style for popup */
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
