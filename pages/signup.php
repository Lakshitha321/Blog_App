<?php 
require_once "functions.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $result = registerUser($username, $email, $password);
        if ($result === 'success') {
            $success = 'Registration successful! You can now login.';
        } else {
            $error = $result;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Blog App</title>
  <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>
  <?php require "header.php"; ?>
  
  <main>
    <div class="container">
      <div class="form-container">
        <h2>Sign Up</h2>
        
        <?php if ($error): ?>
          <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
          <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="post">
          <input type="text" name="username" placeholder="Username" required>
          <input type="email" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Password (min 6 chars)" required>
          <button type="submit">Sign Up</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login here</a></p>
      </div>
    </div>
  </main>
  
  <?php require "footer.php"; ?>
</body>
</html>