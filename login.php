<?php
session_start();
$error = trim($_GET['error'] ?? '');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
require 'backend/db.php';
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: index.php');
    } else {
        $error = 'Invalid login';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - Seed Store</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>
<main class="auth-page">
  <section class="auth-card">
    <a class="auth-back" href="index.php">&larr;</a>
    <h1>Welcome Back</h1>
    <p class="auth-subtitle">Log in to your FarmPro Account</p>

    <div class="auth-socials">
      <button type="button" class="btn btn-social btn-google">Google</button>
      <button type="button" class="btn btn-social btn-facebook">Facebook</button>
    </div>

    <div class="auth-divider"><span>OR CONTINUE WITH EMAIL</span></div>

    <?php if (isset($error)): ?>
      <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" class="auth-form">
      <label>Email Address</label>
      <input name="email" type="email" placeholder="you@example.com" required>

      <label>Password</label>
      <input name="password" type="password" placeholder="********" required>

      <div class="auth-row">
        <label class="checkbox-label"><input type="checkbox" name="remember"> Remember me</label>
        <a href="#">Forgot password?</a>
      </div>

      <button type="submit" class="btn btn-primary auth-submit">Log In</button>
    </form>

    <p class="auth-footer">Don't have an account? <a href="register.php">Sign up</a></p>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>