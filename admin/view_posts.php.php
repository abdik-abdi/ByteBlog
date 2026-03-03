<?php
include 'includes/auth.php';
include 'config/db.php';



$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$filter_sql = "WHERE 1";

if ($category) {
  $filter_sql .= " AND posts.category = '" . mysqli_real_escape_string($conn, $category) . "'";
}

if ($search) {
  $search = mysqli_real_escape_string($conn, $search);
  $filter_sql .= " AND (posts.title LIKE '%$search%' OR posts.content LIKE '%$search%')";
}

$sql = "SELECT posts.*, users.name AS author_name 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        $filter_sql
        ORDER BY posts.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Posts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
 <?php include 'includes/navbar.php'; ?>

<div class="container mt-4">
  <h2>📚 Blog Posts</h2>

  <!-- Search Form -->
  <form method="GET" class="mb-4">
    <div class="input-group w-50">
      <input type="text" name="search" class="form-control" placeholder="Search posts..." value="<?php echo htmlspecialchars($search); ?>">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </form>

  <!-- Category Filter -->
  <form method="GET" class="mb-4">
    <select name="category" onchange="this.form.submit()" class="form-select w-25">
      <option value="">All Categories</option>
      <option value="Food" <?php if ($category == 'Food') echo 'selected'; ?>>Food</option>
      <option value="Travel" <?php if ($category == 'Travel') echo 'selected'; ?>>Travel</option>
      <option value="Theory" <?php if ($category == 'Theory') echo 'selected'; ?>>Theory</option>
    </select>
  </form>

  <!-- Posts Loop -->
  <?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($post = mysqli_fetch_assoc($result)): ?>
      <div class="card mb-4">
        <div class="card-body">
          <?php if (!empty($post['image'])): ?>
        <img src="<?php echo 'assets/images/' . htmlspecialchars(basename($post['image'])); ?>" class="img-fluid mb-3" alt="Post Image">
          <?php endif; ?>

          <h4 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h4>
          <h6 class="card-subtitle mb-2 text-muted">
            Category: <?php echo htmlspecialchars($post['category'] ?? 'Uncategorized'); ?> |
            Author: <?php echo htmlspecialchars($post['author_name']); ?> |
            Date: <?php echo date("M d, Y", strtotime($post['created_at'])); ?>
          </h6>
          <form action="create_post.php" method="POST" enctype="multipart/form-data">
  ...
               <?php if (!empty($post['image'])): ?>
          <img src="<?php echo htmlspecialchars($post['image']); ?>" class="img-fluid mb-3" alt="Post Image">
        <?php endif; ?>


          <p class="card-text"><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 300))); ?>...</p>

          <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-secondary">Read More</a>
          <button class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('<?php echo "http://localhost/ByteBlog/post.php?id=" . $post['id']; ?>')">Share</button>


          <!-- Comment Form -->
          <form action="add_comment.php" method="POST" class="mt-3">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <textarea name="content" class="form-control" placeholder="Write a comment..." required></textarea>
            <button type="submit" class="btn btn-sm btn-primary mt-2">Comment</button>
          </form>

          <!-- Show Comments -->
          <?php
          $pid = $post['id'];
          $comment_sql = "SELECT comments.*, users.name AS commenter 
                          FROM comments 
                          JOIN users ON comments.user_id = users.id 
                          WHERE post_id='$pid' 
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
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert alert-warning">No posts found for your search or filter.</div>
  <?php endif; ?>
</div>
<script>
function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(() => {
    alert("🔗 Link copied to clipboard!");
  }).catch(err => {
    alert("❌ Failed to copy link.");
  });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
