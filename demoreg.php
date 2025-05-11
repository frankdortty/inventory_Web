<?php
session_start();
$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        // For demo, save in session or replace with DB logic
        $_SESSION['registered_user'] = $username;
        $_SESSION['registered_pass'] = password_hash($password, PASSWORD_DEFAULT);
        $success = 'Registration successful. You can now <a href="login.php">login</a>.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <style>
    body {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    .register-container {
      background-color: #fff;
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .register-container h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #333;
    }

    .form-group {
      margin-bottom: 1.2rem;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      color: #555;
    }

    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }

    .show-password {
      margin-top: 0.5rem;
      font-size: 0.9rem;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 1rem;
    }

    .success {
      color: green;
      text-align: center;
      margin-bottom: 1rem;
    }

    .btn-register {
      width: 100%;
      padding: 0.8rem;
      border: none;
      background-color: #4facfe;
      color: white;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn-register:hover {
      background-color: #00c3ff;
    }
  </style>
</head>
<body>
  <div class="register-container">
    <h2>Register</h2>

    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php elseif ($success): ?>
      <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required />
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" required />
        <div class="show-password">
          <input type="checkbox" id="togglePassword" />
          <label for="togglePassword">Show Password</label>
        </div>
      </div>

      <button class="btn-register" type="submit">Register</button>
    </form>
    <p style="text-align:center; margin-top:10px;">Already have an account? <a href="demo.php">Login here</a>.</p>
  </div>

  <script>
    const toggle = document.getElementById('togglePassword');
    const passwordFields = [document.getElementById('password'), document.getElementById('confirm_password')];

    toggle.addEventListener('change', function () {
      passwordFields.forEach(field => {
        field.type = this.checked ? 'text' : 'password';
      });
    });
  </script>
</body>
</html>
