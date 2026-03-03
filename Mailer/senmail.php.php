<html>
<head>
  <title>EmailByteBlog</title>
</head>
<body bgcolor="#EAEAEA">
  <h3>Email</h3>
  <form method="post">
    <table>
      <tr>
        <td>To:</td>
        <td><input type="email" name="to" required /></td>
      </tr>
      <tr>
        <td>Subject:</td>
        <td><input type="text" name="subject" required /></td>
      </tr>
    </table>
    <textarea name="message" cols="50" rows="5" required></textarea>
    <br/>
    <input type="submit" value="Send"/>
  </form>
  <br/><br/>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = trim($_POST["to"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    echo "Sending to: $to<br>";
    echo "Subject: $subject<br>";
    echo "Message: $message<br>";

    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        echo "<div style='color:red;'>❌ Invalid email address</div>";
    } else {
        $from = "no-reply@byteblog.com"; // Replace with your domain or SMTP sender
        $headers = "From: $from\r\n";
        $headers .= "Reply-To: $from\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        $sent = mail($to, $subject, $message, $headers);

        if ($sent) {
            echo "<div style='color:green;'> Message sent successfully to $to</div>";
        } else {
            echo "<div style='color:red;'>Error sending message</div>";
        }
    }
}
?>
</body>
</html>
