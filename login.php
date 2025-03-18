<?php 
session_start();
include('const/db.php'); // Include database connection

// Handle login logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Check if all required fields are present
        if (!isset($_POST['username'], $_POST['password'], $_POST['companyName'])) {
            throw new Exception('Missing required fields');
        }

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $companyName = trim($_POST['companyName']);

        // Prepare and execute query to fetch user data
        $query = "SELECT * FROM users WHERE username = :username AND company_name = :companyName";
        $stmt = $pdo->prepare($query);

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':companyName', $companyName);

        // Execute the statement
        $stmt->execute();

        // Check if any rows were returned
        if ($stmt->rowCount() > 0) {
            // Fetch the user data
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the entered password against the stored hashed password
            if (password_verify($password, $user['password'])) {
                // Store user details in session
                $_SESSION['username'] = $username;
                $_SESSION['companyName'] = $companyName;

                // Redirect to home page
                header("Location: home.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Invalid credentials";
            }
        } else {
            $_SESSION['error_message'] = "Invalid credentials";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
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
                    unset($_SESSION['error_message']); // Clear the error after displaying
                }
                ?>
                <form  method="post">
                    <input type="text" name="username" class="input-box" placeholder="Username" required>
                    <input type="password" name="password" class="input-box" placeholder="Password" required>
                    <input type="text" name="companyName" class="input-box" placeholder="Company Name" required>
                    <button type="submit" class="login-btn">Login</button>
                </form>
                <p class="register-link">Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </div>
    </div>
</body>
</html>
