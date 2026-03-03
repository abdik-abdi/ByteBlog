<?php
include 'includes/auth.php';
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $sql = "INSERT INTO comments (post_id, user_id, content) VALUES ('$post_id', '$user_id', '$content')";
    mysqli_query($conn, $sql);
    header("Location: view_posts.php");
    exit();
}
?>
