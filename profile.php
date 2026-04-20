<?php
session_start();
if (!isset($_SESSION['user'])) { 
    header('Location: login.php'); 
    exit; 
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="auth-card" style="max-width: 520px; margin: 0 auto;">
    <h1>Your Profile</h1>
    <p class="auth-subtitle">Account information</p>
    
    <div class="card" style="text-align: center; padding: 24px;">
      <h2 style="color: var(--accent2); margin-top: 0;"><?php echo htmlspecialchars($user['name']); ?></h2>
      <p style="font-size: 1.1rem; color: var(--muted); margin: 8px 0;"><?php echo htmlspecialchars($user['email']); ?></p>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 24px;">
      <a class="btn btn-primary" href="my-orders.php">My Orders</a>
      <a class="btn btn-secondary" href="#">Edit Profile</a>
    </div>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
