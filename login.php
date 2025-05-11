<?php
// Include database connection
include('const/db.php');

// Initialize variables
$username = $password = $companyName = "";
$errorMessage = "";

// Start the session
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $companyName = $_POST['companyName'];

    // Prepare SQL query to fetch user details based on username and company name
    $query = "SELECT * FROM users WHERE username = ? AND company_name = ?";
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters
        $stmt->bind_param("ss", $username, $companyName);

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any row was returned (user exists)
        if ($result->num_rows == 1) {
            // Fetch the user data
            $user = $result->fetch_assoc();

            // Verify the password (assuming the password is hashed using password_hash())
            if (password_verify($password, $user['password'])) {
                // Correct login, store company name in session
                $_SESSION['companyName'] = $companyName; 
                // Redirect to home.php
                header("Location: dashboard.php");
                exit();
            } else {
                $errorMessage = "Incorrect password.";
            }
        } else {
            $errorMessage = "Username or company name is incorrect.";
        }

        // Close the statement
        $stmt->close();
    } else {
        $errorMessage = "Database query failed.";
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<?php include("const/head.php"); ?>
</head>
<body>
    <div class="wholeLogin">
        <div class="container">
            <div class="header">
                <div class="profile-icon">L</div>
            </div>
            <div class="form">
                <form method="post">
                    <input type="text" name="username" class="input-box" placeholder="Username" required>
                    <input type="password" name="password" class="input-box" placeholder="Password" required>
                    <input type="text" name="companyName" class="input-box" placeholder="Company Name" required>
                    <button type="submit" class="login-btn">Login</button>
                </form>
                <?php if ($errorMessage != ""): ?>
                    <p class="error-message"><?php echo $errorMessage; ?></p>
                <?php endif; ?>
                <p class="register-link">Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </div>
    </div>
</body>
</html>
