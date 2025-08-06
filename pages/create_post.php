<?php 
require_once "functions.php";

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all fields';
    } else {
        $result = createPost($title, $content, $_SESSION['user_id']);
        if ($result === 'success') {
            $success = 'Post created successfully!';
            // Clear form
            $title = '';
            $content = '';
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
  <title>Create Post - Blog App</title>
  <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>
  <?php require "header.php"; ?>
  
  <main>
    <div class="container">
      <div class="form-container">
        <h2>Create New Post</h2>
        
        <?php if ($error): ?>
          <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
          <div class="success"><?php echo $success; ?> <a href="profile.php">View your posts</a></div>
        <?php endif; ?>
        
        <form method="post">
          <input type="text" name="title" placeholder="Post Title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" required>
          <textarea name="content" placeholder="Write your post content here..." rows="10" required><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
          <button type="submit">Publish Post</button>
        </form>
      </div>
    </div>
  </main>
  
  <?php require "footer.php"; ?>
</body>
</html>
