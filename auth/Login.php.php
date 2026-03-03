<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Login</title>
</head>
<style css>
 body {
   font-family: Arial, sans-serif;
   margin: 20px;
 }
 form {
   max-width: 400px;
   margin: auto;
   background-color: #f9f9f9;
   padding: 20px;
   border-radius: 8px;
   box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
 }
 input[type="email"], input[type="password"] {
   width: 100%;
   padding: 10px;
   margin: 10px 0;
   border: 1px solid #ccc;
   border-radius: 4px;
   box-sizing: border-box;
 }
 button {
   background-color: #007BFF;
   color: white;
   border: none;
   border-radius: 4px;
   padding: 10px 20px;
   cursor: pointer;
 }
</style>
<body>
 <h1>Login</h1>
 <form action="login.php" method="POST">
   <label for="email">Email:</label>
   <input type="email" name="email" required><br>

   <label for="password">Password:</label>
   <input type="password" name="password" required><br>

   <button type="submit">Login</button>
   <br>
   <p>Don't have an account? <a href="register.php">Register here</a></p>
 </form>
  <?php

include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that email.";
    }
}
?>



 <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>

 
</body>
</html>
