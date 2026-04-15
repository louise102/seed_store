<?php
session_start();
if (!isset($_SESSION['user'])) { 
    header('Location: login.php'); 
    exit; 
}
require 'backend/db.php';
$user_id = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Orders - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="card">
    <h1>My Orders</h1>
    <p class="auth-subtitle">Track your seed purchases</p>
    
    <?php if ($orders): ?>
      <div style="overflow-x: auto; margin-top: 24px;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: var(--panel);">
              <th style="padding: 16px 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Order ID</th>
              <th style="padding: 16px 12px; text-align: center;">Date</th>
              <th style="padding: 16px 12px; text-align: right;">Total</th>
              <th style="padding: 16px 12px; text-align: center;">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 16px 12px; font-weight: 600;">#<?=$order['id']?></td>
              <td style="padding: 16px 12px; color: var(--muted);"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
              <td style="padding: 16px 12px; text-align: right; font-weight: 700; color: var(--accent2);">₱<?=$order['total']?></td>
              <td style="padding: 16px 12px; text-align: center;">
                <span style="padding: 4px 12px; background: #e6ffe6; color: #1b5e20; border-radius: 20px; font-size: 0.85rem; font-weight: 600;"><?php echo ucfirst($order['status'] ?? 'Pending'); ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="text-align: center; padding: 60px 40px; color: var(--muted);">
        <h2>No orders yet</h2>
        <p>Your seed orders will appear here. <a href="product.php">Start shopping</a></p>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
