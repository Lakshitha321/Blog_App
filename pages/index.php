<?php require_once "functions.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog App - Home</title>
  <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>
  <?php require_once "header.php"; ?>
  
  <main>
    <div class="container">
      <h1>Welcome to Our Blog</h1>
      
      <?php if (!isLoggedIn()): ?>
        <div class="welcome-message">
          <p>Please <a href="login.php">login</a> or <a href="signup.php">sign up</a> to start blogging!</p>
        </div>
      <?php endif; ?>
      
      <section class="posts">
        <h2>Recent Posts</h2>
        <?php 
        $posts = getAllPosts();
        if (empty($posts)): ?>
          <p>No posts yet. Be the first to write one!</p>
        <?php else: ?>
          <?php foreach ($posts as $post): ?>
            <article class="post">
              <h3><?php echo htmlspecialchars($post['title']); ?></h3>
              <div class="post-meta">
                By <?php echo htmlspecialchars($post['username']); ?> on 
                <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
              </div>
              <div class="post-content">
                <?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200))); ?>
                <?php if (strlen($post['content']) > 200): ?>
                  <span>...</span>
                <?php endif; ?>
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