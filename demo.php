<?php
session_start();

// Simple dummy credentials (replace with DB check in production)
$validUser = 'admin';
$validPass = 'password123';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === $validUser && $password === $validPass) {
        $_SESSION['user'] = $username;
        header('Location: marketp.php'); // Dummy landing page
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <style>
    body {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      padding: 22px;
    }

    .login-container {
      background-color: #fff;
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .login-container h2 {
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

    .btn-login {
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

    .btn-login:hover {
      background-color: #00c3ff;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>

    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required />
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required />
        <div class="show-password">
          <input type="checkbox" id="togglePassword" />
          <label for="togglePassword">Show Password</label>
        </div>
      </div>

      <button class="btn-login" type="submit">Login</button>
    </form>
    <p style="text-align:center; margin-top:10px;">Don't have an account? <a href="demoreg.php">Register here</a>.</p>
  </div>

  <script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    toggle.addEventListener('change', function () {
      password.type = this.checked ? 'text' : 'password';
    });
  </script>
</body>
</html>
