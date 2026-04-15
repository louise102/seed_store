<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    // Send email or save to DB
    echo "Thank you for contacting us!";
}
?>
<!DOCTYPE html>
<html>
<head><title>Contact Form</title></head>
<body>
<?php include 'header.php'; ?>
<h1>Contact Form</h1>
<form method="post">
  Name: <input name="name" required><br>
  Email: <input name="email" type="email" required><br>
  Message: <textarea name="message" required></textarea><br>
  <button type="submit">Send</button>
</form>
<?php include 'footer.php'; ?>
</body>
</html>