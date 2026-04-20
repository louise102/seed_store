<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username === 'admin@gmail.com' && $password === 'admin') {
        $_SESSION['admin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials. Email: ' . htmlspecialchars($username) . ', Password length: ' . strlen($password);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login - Seed Store</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="auth-page">
  <section class="auth-card">
    <a class="auth-back" href="index.php">&larr;</a>
    <h1>Admin Access</h1>
    <p class="auth-subtitle">Log in to your Admin Dashboard</p>

    <div class="auth-divider"><span>ADMIN LOGIN</span></div>

    <?php if (isset($error)): ?>
      <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" class="auth-form">
      <label>Email</label>
      <input name="username" placeholder="admin@gmail.com" required>

      <label>Password</label>
      <input name="password" type="password" placeholder="********" required>

      <div class="auth-row">
        <label class="checkbox-label"><input type="checkbox" name="remember"> Remember me</label>
      </div>

      <button type="submit" class="btn btn-primary auth-submit">Sign In</button>
    </form>

    <p class="auth-footer">Don't have an account? <a href="admin.register.php">Create one</a></p>
  </section>
</main>
</body>
</html>