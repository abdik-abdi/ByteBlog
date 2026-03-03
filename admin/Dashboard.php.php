<?php
session_start(); // ✅ Start session first

include 'includes/auth.php'; // ✅ Protects the page
include 'config/db.php';

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
?>



<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Dashboard</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">BYTEBLOG</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="Editprof.php">Edit Profile</a></li>
        <li class="nav-item"><a class="nav-link" href="create_post.php">Create Post</a></li>
        <li class="nav-item"><a class="nav-link" href="view_posts.php">View Posts</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2>Welcome, <?php echo htmlspecialchars($user_name); ?> 👋</h2>
  <p>Manage your profile, share posts, and explore the blog.</p>
  <?php
$sql = "SELECT title, content, created_at FROM posts ORDER BY created_at DESC LIMIT 5";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-4">
  <h4>📝 Recent Posts</h4>
  <?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($post = mysqli_fetch_assoc($result)): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
          <p class="card-text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
          <p class="card-text"><small class="text-muted">Posted on <?php echo date("F j, Y, g:i a", strtotime($post['created_at'])); ?></small></p>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No posts yet. Start by creating one!</p>
  <?php endif; ?>
</div>


  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">✅ Profile updated successfully!</div>
  <?php endif; ?>

  <?php if (isset($_GET['post'])): ?>
    <div class="alert alert-success">✅ Post published successfully!</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
