<?php
include 'includes/auth.php';
include 'config/db.php';

require_once 'includes/Parsedown.php';
$Parsedown = new Parsedown();



if (!isset($_GET['id'])) {
    echo "Post not found.";
    exit();
}

$post_id = $_GET['id'];

// Fetch the post
$sql = "SELECT posts.*, users.name AS author_name 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.id = '$post_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) !== 1) {
    echo "Post not found.";
    exit();
}

$post = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($post['title']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
 <?php include 'includes/navbar.php'; ?>

<div class="container mt-4">
  <a href="view_posts.php" class="btn btn-sm btn-secondary mb-3">← Back to Posts</a>

      <h2><?php echo htmlspecialchars($post['title']); ?></h2>
      <h6 class="text-muted">
        Category: <?php echo htmlspecialchars($post['category']); ?> |
        Author: <?php echo htmlspecialchars($post['author_name']); ?> |
        Date: <?php echo date("M d, Y", strtotime($post['created_at'])); ?>
      </h6>
      <?php echo $Parsedown->text($post['content']); ?>


      <!-- Share Button -->
      <button class="btn btn-sm btn-outline-primary" onclick="navigator.clipboard.writeText(window.location.href)">Copy Share Link</button>

      <!-- Comment Form -->
      <form action="add_comment.php" method="POST" class="mt-4">
        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
        <textarea name="content" class="form-control" placeholder="Write a comment..." required></textarea>
        <button type="submit" class="btn btn-sm btn-primary mt-2">Comment</button>
      </form>

      <!-- Show Comments -->
      <h5 class="mt-4">Comments</h5>
      <?php
      $comment_sql = "SELECT comments.*, users.name AS commenter 
                      FROM comments 
                      JOIN users ON comments.user_id = users.id 
                      WHERE post_id='$post_id' 
                      ORDER BY created_at DESC";
      $comment_result = mysqli_query($conn, $comment_sql);
      while ($comment = mysqli_fetch_assoc($comment_result)):
      ?>
        <div class="mt-2 border p-2">
          <strong><?php echo htmlspecialchars($comment['commenter']); ?>:</strong>
          <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
          <small class="text-muted"><?php echo date("M d, Y H:i", strtotime($comment['created_at'])); ?></small>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
