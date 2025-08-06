<?php 
require_once "functions.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $result = loginUser($email, $password);
        if ($result === 'success') {
            header('Location: index.php');
            exit();
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
  <title>Login - Blog App</title>
  <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>
  <?php require "header.php"; ?>
  
  <main>
    <div class="container">
      <div class="form-container">
        <h2>Login</h2>
        
        <?php if ($error): ?>
          <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post">
          <input type="email" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit">Login</button>
        </form>
        
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
      </div>
    </div>
  </main>
  
  <?php require "footer.php"; ?>
</body>
</html>