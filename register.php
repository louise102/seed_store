<?php
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'backend/db.php';
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$name || !$email || !$password) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'That email is already registered. Please log in or use a different email.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO customers (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $passwordHash]);
            header('Location: login.php');
            exit;
        }
    }
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

    <?php if ($error): ?>
      <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" class="auth-form">
      <label>Username</label>
      <input name="name" placeholder="Your full name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>

      <label>Email Address</label>
      <input name="email" type="email" placeholder="you@example.com" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>

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