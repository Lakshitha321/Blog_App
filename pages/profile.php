<?php 
require_once "functions.php";

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$userPosts = getUserPosts($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - Blog App</title>
  <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>
  <?php require "header.php"; ?>
  
  <main>
    <div class="container">
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
      
      <div class="profile-actions">
        <a href="create_post.php" class="btn">Create New Post</a>
      </div>
      
      <section class="user-posts">
        <h2>Your Posts</h2>
        <?php if (empty($userPosts)): ?>
          <p>You haven't written any posts yet. <a href="create_post.php">Create your first post!</a></p>
        <?php else: ?>
          <?php foreach ($userPosts as $post): ?>
            <article class="post">
              <h3><?php echo htmlspecialchars($post['title']); ?></h3>
              <div class="post-meta">
                Published on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
              </div>
              <div class="post-content">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
              </div>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </div>
  </main>
  
  <?php require "footer.php"; ?>
</body>
</html>