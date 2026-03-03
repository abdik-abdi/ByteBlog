

<div class="container">
    <h1>Welcome to ByteBlog</h1>
    <hr>

    <div class="blog-posts">
        <?php
        // 3. Fetch posts from the database
        $sql = "SELECT * FROM posts ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo "<div class='post-card'>";
                // Note: We use the new 'Images/' folder path here
                echo "<img src='Images/" . $row['image'] . "' width='200'>";
                echo "<h2>" . $row['title'] . "</h2>";
                echo "<p>" . substr($row['content'], 0, 100) . "...</p>";
                echo "<a href='posts/post.php?id=" . $row['id'] . "'>Read More</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No posts found. Why not <a href='posts/create_post.php'>create one</a>?</p>";
        }
        ?>
    </div>
</div>

<?php 
// 4. Include the footer
include('includes/footer.php'); 
?>