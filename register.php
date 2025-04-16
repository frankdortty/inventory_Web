<?php
// Include database connection
include('const/db.php');

// Initialize variables
$username = $fullName = $email = $password = $companyName = "";
$errorMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST['username'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $companyName = $_POST['companyName'];

    // Check if email already exists
    $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($checkEmailQuery)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errorMessage = "Error: This email is already registered. Please use a different email.";
        } else {
            // Proceed with inserting the new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $createdAt = date("Y-m-d H:i:s");

            $insertQuery = "INSERT INTO users (username, full_name, email, password, company_name, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?)";

            if ($stmt = $conn->prepare($insertQuery)) {
                $stmt->bind_param("ssssss", $username, $fullName, $email, $hashedPassword, $companyName, $createdAt);

                if ($stmt->execute()) {
                    // Redirect to login page after successful registration
                    header("Location: login.php");
                    exit();
                } else {
                    $errorMessage = "Error: Could not register the user. Please try again.";
                }

                $stmt->close();
            } else {
                $errorMessage = "Database query failed.";
            }
        }

        $stmt->close();
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
                <!-- Display error messages if any -->
                <?php if ($errorMessage != ""): ?>
                    <p class="error-message"><?php echo $errorMessage; ?></p>
                <?php endif; ?>
                
                <form action="register.php" method="post">
                    <input type="text" name="username" class="input-box" placeholder="Username" required>
                    <input type="text" name="fullName" class="input-box" placeholder="Full Name" required>
                    <input type="email" name="email" class="input-box" placeholder="Email" required>
                    <input type="password" name="password" class="input-box" placeholder="Password" required>
                    <input type="text" name="companyName" class="input-box" placeholder="Company Name" required>
                    <button type="submit" class="login-btn">Register</button>
                </form>
                <p class="register-link">Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
