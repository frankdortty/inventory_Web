<?php 
session_start();
include('const/db.php'); // Include database connection

// Handle registration logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Validate required fields
        if (!isset($_POST['username'], $_POST['fullName'], $_POST['email'], $_POST['password'], $_POST['companyName']) ||
            empty($_POST['username']) || empty($_POST['fullName']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['companyName'])) {
            throw new Exception('All fields are required and must not be empty.');
        }

        $username = trim($_POST['username']);
        $fullName = trim($_POST['fullName']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing
        $companyName = trim($_POST['companyName']);

        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            throw new Exception('Username or email already exists.');
        }

        // Insert user data into database
        $stmt = $pdo->prepare("
            INSERT INTO users (username, full_name, email, password, company_name) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$username, $fullName, $email, $password, $companyName]);

        // Registration successful, redirect to login
        $_SESSION['success_message'] = "Registration successful! Please log in.";
        header("Location: login.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
    }
}
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
                <?php
                if (isset($_SESSION['error_message'])) {
                    echo "<p style='color: red;'>" . $_SESSION['error_message'] . "</p>";
                    unset($_SESSION['error_message']); // Clear error after displaying
                }
                ?>
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
