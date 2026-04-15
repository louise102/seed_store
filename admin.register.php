<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $secret = $_POST['secret'] ?? '';
    
    // Simple validation: check secret code (you should store this more securely)
    if ($secret !== 'admin123') {
        $error = 'Invalid admin secret code';
    } else {
        // In production, store admin in database
        $_SESSION['admin'] = true;
        $_SESSION['admin_user'] = $username;
        header('Location: dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Registration - Seed Store</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="auth-page">
  <section class="auth-card">
    <a class="auth-back" href="index.php">&larr;</a>
    <h1>Create Admin Account</h1>
    <p class="auth-subtitle">Set up your admin dashboard access</p>

    <div class="auth-divider"><span>ADMIN REGISTRATION</span></div>

    <?php if (isset($error)): ?>
      <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" class="auth-form">
      <label>Username</label>
      <input name="username" placeholder="admin username" required>

      <label>Password</label>
      <input name="password" type="password" placeholder="********" required>

      <label>Admin Secret Code</label>
      <input name="secret" type="password" placeholder="secret code" required>

      <button type="submit" class="btn btn-primary auth-submit">Create Admin</button>
    </form>

    <p class="auth-footer">Already have an account? <a href="admin.login.php">Log in</a></p>
  </section>
</main>
</body>
</html>
