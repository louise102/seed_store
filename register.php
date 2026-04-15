<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
require 'backend/db.php';
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO customers (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $password]);
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Create Account - Seed Store</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<main class="auth-page">
  <section class="auth-card">
    <a class="auth-back" href="index.php">&larr;</a>
    <h1>Create Your Account</h1>
    <p class="auth-subtitle">Get started by setting up your profile in minutes</p>

    <form method="post" class="auth-form">
      <label>Username</label>
      <input name="name" placeholder="Your full name" required>

      <label>Email Address</label>
      <input name="email" type="email" placeholder="you@example.com" required>

      <label>Password</label>
      <input name="password" type="password" placeholder="********" required>

      <button type="submit" class="btn btn-primary auth-submit">Register</button>
    </form>

    <p class="auth-footer">Already have an account? <a href="login.php">Log in</a></p>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>