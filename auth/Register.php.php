<?php
include 'config/db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<style>
  body {
    font-family:  sans-serif;
    margin: 20px;
  }
  form {
    max-width: 400px;
    margin: auto;
  }
  label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}
  
  input, select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
  }
  button {
    margin-top: 15px;
    padding: 10px 15px;
  }
  form {
  max-width: 500px;
  margin: 40px auto;
  background-color: #fff;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

input, select {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

button {
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
</style>
<body>

<h1>Register</h1>

<form action="register.php" method="POST" enctype="multipart/form-data">
  <label for="name">First Name:</label>
  <input type="text" name="name" required><br>

  <label for="Surname">Surname:</label>
  <input type="text" name="Surname" required><br>

  <label for="Gender">Gender:</label>
  <select id="Gender" name="Gender" required>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
    <option value="Other">Other</option>
  </select><br>

  <label for="dob">Date Of Birth:</label>
  <input type="date" name="dob" required><br>

  <label for="email">Email:</label>
  <input type="email" name="email" required><br>

  <label for="contact">Contact:</label>
  <input type="tel" name="contact" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="e.g., 123-456-7890"><br>

  <label for="photo">Upload Photo:</label>
  <input type="file" name="photo" accept="image/*" required><br>

  <button type="submit">Register</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['Surname']);
    $gender = mysqli_real_escape_string($conn, $_POST['Gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);

    $plain_password = bin2hex(random_bytes(4));
    $hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

    $photo = $_FILES['photo']['name'];
    $target_dir = "assets/images/";
    $target_file = $target_dir . basename($photo);

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        echo "📸 Photo uploaded successfully.<br>";

        $sql = "INSERT INTO users (name, surname, gender, dob, email, contact, password, photo)
                VALUES ('$name', '$surname', '$gender', '$dob', '$email', '$contact', '$hashed_password', '$target_file')";

        if (mysqli_query($conn, $sql)) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'upcxming@gmail.com'; // your Gmail
                $mail->Password   = 'yulv vnzl ybvi pjwm';  // your App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('yourgmail@gmail.com', 'BYTEBLOG');
                $mail->addAddress($email);

                $mail->isHTML(false);
                $mail->Subject = "Welcome to BYTEBLOG!";
                $mail->Body    = "Hi $name,\n\nYour account has been created.\n\nEmail: $email\nPassword: $plain_password\n\nPlease log in and change your password.";
                
                $mail->isHTML(true);
$mail->Subject = "Welcome to BYTEBLOG!";
$mail->Body = "
  <h3>Hi $name,</h3>
  <p>Your account has been created.</p>
  <p><strong>Email:</strong> $email<br>
     <strong>Password:</strong> $plain_password</p>
  <p><a href='http://localhost/ByteBlog/login.php'>👉 Click here to log in</a></p>
  <p>🔒 Please change your password after logging in.</p>
";
header("Location: login.php?registered=true");
exit();



                $mail->send();
                echo "<div style='color:green;'> Registration successful. Password sent to $email</div>";
            } catch (Exception $e) {
                echo "<div style='color:red;'> Email failed: {$mail->ErrorInfo}</div>";
            }
        } else {
            echo "<div style='color:red;'> Error: " . mysqli_error($conn) . "</div>";
        }
    } else {
        echo "<div style='color:red;'> Error uploading photo.</div>";
    }
}
?>

</body>
</html>
