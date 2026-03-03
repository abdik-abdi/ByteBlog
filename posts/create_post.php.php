<?php
include 'includes/auth.php';
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id  = $_SESSION['user_id'];
    $title    = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $content  = mysqli_real_escape_string($conn, $_POST['content']);

    // Handle image upload
    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp  = $_FILES['image']['tmp_name'];
        $image_path = "assets/images/" . basename($image_name);

        if (!move_uploaded_file($image_tmp, $image_path)) {
            echo "<div style='color:red;'>❌ Failed to upload image.</div>";
        }
    }

    // Insert post with image path
    $sql = "INSERT INTO posts (user_id, title, category, content, image)
            VALUES ('$user_id', '$title', '$category', '$content', '$image_path')";

    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?post=success");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
 <?php include 'includes/navbar.php'; ?>

<div class="container mt-5">
  <h2>Create a New Post</h2>
  <form action="create_post.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="title" class="form-label">Title:</label>
      <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="category" class="form-label">Category:</label>
      <select name="category" class="form-select">
        <option value="Food">Food</option>
        <option value="Travel">Travel</option>
        <option value="Theory">Theory</option>
        <option value="Technology">Technology</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="image" class="form-label">Upload image:</label>
      <input type="file" name="image" class="form-control">
    </div>

    <div class="mb-3">
      <label for="content" class="form-label">Content:</label>
      <textarea name="content" rows="6" class="form-control" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Publish</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
