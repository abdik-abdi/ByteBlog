<?php
include 'includes/auth.php';
include 'config/db.php';

$user_id = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $surname  = mysqli_real_escape_string($conn, $_POST['Surname']);
    $gender   = mysqli_real_escape_string($conn, $_POST['Gender']);
    $dob      = mysqli_real_escape_string($conn, $_POST['dob']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $contact  = mysqli_real_escape_string($conn, $_POST['contact']);
    

    if (!empty($_POST['new_password'])) {
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update password in database
    $sql_pw = "UPDATE users SET password='$hashed_password' WHERE id='$user_id'";
    mysqli_query($conn, $sql_pw);
}



    // photo
    $sql = "SELECT photo FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    $photo = $user['photo'];

    // Update photo 
    if (!empty($_FILES['photo']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['photo']['type'];
        $file_size = $_FILES['photo']['size'];

        if (in_array($file_type, $allowed_types) && $file_size <= 2 * 1024 * 1024) {
            $new_photo = basename($_FILES['photo']['name']);
            $target_dir = "assets/images/";
            $target_file = $target_dir . $new_photo;

            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = $target_file;
            } else {
                echo "Error uploading photo.";
            }
        } else {
            echo "Invalid file type or size. Only JPG, PNG, GIF under 2MB allowed.";
        }
    }

    // Update user profile
    $sql = "UPDATE users SET 
                name='$name', 
                surname='$surname', 
                gender='$gender', 
                dob='$dob', 
                email='$email', 
                contact='$contact', 
                photo='$photo' 
            WHERE id='$user_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['user_name'] = $name;
        $_SESSION['email'] = $email;
        header("Location: dashboard.php?updated=true");
        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}

// Fetch user data for form
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Edit Profile</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
  form label {
    display: block;
    margin-top: 10px;
  } 
  form input, form select {
    width: 300px;
    padding: 5px;
  }
  form button {
    margin-top: 15px;
    padding: 10px 20px;
  }
  body {
    font-family: Arial, sans-serif;
    margin: 20px;
  }
  form {
    max-width: 500px;
    margin: auto;
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  } input, select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  } button {
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
</style>
<body>
<?php include 'includes/navbar.php'; ?>

<h1>Edit Profile</h1>

<form action="edit_profile.php" method="POST" enctype="multipart/form-data">
  <label for="name">First Name:</label>
  <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>

  <label for="Surname">Surname:</label>
  <input type="text" name="Surname" value="<?php echo htmlspecialchars($user['surname']); ?>" required><br>

  <label for="Gender">Gender:</label>
  <select id="Gender" name="Gender" required>
    <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
    <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
    <option value="Other" <?php if ($user['gender'] == 'Other') echo 'selected'; ?>>Other</option>
  </select><br>

  <label for="dob">Date Of Birth:</label>
  <input type="date" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required><br>

  <label for="email">Email:</label>
  <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

  <label for="contact">Contact:</label><br>
  <input type="tel" name="contact" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="e.g., 123-456-7890" value="<?php echo htmlspecialchars($user['contact']); ?>"><br>

  <label for="new_password">New Password:</label>
<input type="password" name="new_password" placeholder="Enter new password"><br>



  <label for="photo">Upload Photo:</label><br>
  <input type="file" name="photo" accept="image/*">
  <br>

  <button type="submit">Update Profile</button>
</form>

</body>
</html>
