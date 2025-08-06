<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog App</title>
  <link rel="stylesheet" href="/Styles/styles.css">
</head>
<body>
<header>
  <div><a href="index.php">Home</a></div>
  <?php if (isset($_SESSION['user_id'])): ?>
    <div><a href="profile.php">Profile</a></div>
    <div><a href="create_post.php">New Post</a></div>
    <div><a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a></div>
  <?php else: ?>
    <div><a href="login.php">Login</a></div>
    <div><a href="signup.php">Sign Up</a></div>
  <?php endif; ?>
</header>